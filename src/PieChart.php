<?php


namespace Jdlabs\NovaMetrics;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jdlabs\NovaMetrics\Traits\Chartable;
use Jdlabs\NovaMetrics\Traits\Refreshable;
use Laravel\Nova\Card;
use Laravel\Nova\Metrics\Partition;
use Laravel\Nova\Metrics\PartitionResult;

class PieChart extends Partition
{

    use Chartable, Refreshable;

    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = '1/3';

    /**
     * Card's height
     *
     * @var int
     */
    public $height = 1;

    /**
     * Set if wants to display donut or not
     *
     * @var bool
     */
    public $donut = false;

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'DynamicPieChartMetric';
    }

    /**
     * Id of the chart
     *
     * @return string
     */
    protected function id()
    {
        return 'jdlabs-piechart';
    }

    /**
     * Set donut value
     *
     * @param  bool $donut
     * @return $this
     */
    public function donut(bool $donut = true)
    {
        $this->donut = $donut;
        return $this;
    }

    /**
     * Return a partition result showing the segments of a aggregate.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string  $function
     * @param  string  $column
     * @param  string  $groupBy
     * @return \Laravel\Nova\Metrics\PartitionResult
     */
    protected function aggregate($request, $model, $function, $column, $groupBy)
    {
        $query = $model instanceof Builder ? $model : (new $model)->newQuery();

        if (is_callable($column)) {
            $query = $column($query);
            $results =  $query->selectRaw("{$groupBy}")
                ->groupBy($groupBy)
                ->get();
        } else {
            $wrappedColumn = $query->getQuery()->getGrammar()->wrap(
                $column = $column ?? $query->getModel()->getQualifiedKeyName()
            );

            $results = $query->select(
                $groupBy, DB::raw("{$function}({$wrappedColumn}) as aggregate")
            )->groupBy($groupBy)->get();

        }

        return $this->result($results->mapWithKeys(function ($result) use ($groupBy) {
            return $this->formatAggregateResult($result, $groupBy);
        })->all());
    }

    /**
     * Return the meta data to be used on the pie charts
     *
     * @return array|void
     */
    public function meta()
    {
        return [
            'meta' => [
                'colors' => $this->colors(),
                'cardHeight' => $this->getHeight(),
                'donut' => $this->donut,
                'refreshRate' => $this->refreshRate()
            ]
        ];
    }

}

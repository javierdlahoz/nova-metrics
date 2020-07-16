<?php


namespace Jdlabs\NovaMetrics;


use Cake\Chronos\Chronos;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Jdlabs\NovaMetrics\Traits\Chartable;
use Jdlabs\NovaMetrics\Traits\Refreshable;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Metrics\TrendDateExpressionFactory;
use Laravel\Nova\Nova;

class AdvancedTrend extends Trend
{
    use Chartable, Refreshable;

    /**
     * Card's width
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
     * Vue's component
     *
     * @var string
     */
    public $component = 'DynamicAdvancedTrendMetric';

    /**
     * Show/Hide markers
     *
     * @return false
     */
    public function showMarkers()
    {
        return true;
    }

    /**
     * Return the meta data to be used on the pie charts
     *
     * @return array|void
     */
    public function meta()
    {
        return array_merge([
            'meta' => [
                'cardHeight' => $this->getHeight(),
                'showMarkers' => $this->showMarkers(),
                'refreshRate' => $this->refreshRate(),
                'colors' => $this->colors()
            ]
        ], $this->withMeta([]));
    }

    /**
     * Return a value result showing a aggregate over time.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string  $unit
     * @param  string  $function
     * @param  string  $column
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    protected function aggregate($request, $model, $unit, $function, $column, $dateColumn = null)
    {
        $query = $model instanceof Builder ? $model : (new $model)->newQuery();

        $timezone = Nova::resolveUserTimezone($request) ?? $request->timezone;

        $expression = (string) TrendDateExpressionFactory::make(
            $query, $dateColumn = $dateColumn ?? $query->getModel()->getCreatedAtColumn(),
            $unit, $timezone
        );

        $possibleDateResults = $this->getAllPossibleDateResults(
            $startingDate = $this->getAggregateStartingDate($request, $unit),
            $endingDate = Chronos::now(),
            $unit,
            $timezone,
            $request->twelveHourTime === 'true'
        );

        if (is_string($column)) {
            $query->select(DB::raw("{$expression} as date_result, {$function}({$column}) as aggregate"));
        } elseif (is_callable($column)) {
            $query = $column($query);
        }

        $results = $query
            ->whereBetween($dateColumn, [$startingDate, $endingDate])
            ->groupBy(DB::raw($expression))
            ->orderBy('date_result')
            ->get();

        $results = array_merge($possibleDateResults, $results->mapWithKeys(function ($result) use ($request, $unit) {
            return [$this->formatAggregateResultDate(
                $result->date_result, $unit, $request->twelveHourTime === 'true'
            ) => round($result->aggregate, $this->precision)];
        })->all());

        if (count($results) > $request->range) {
            array_shift($results);
        }

        return $this->result()->trend(
            $results
        );
    }
}

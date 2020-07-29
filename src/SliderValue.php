<?php


namespace Jdlabs\NovaMetrics;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Jdlabs\NovaMetrics\Traits\Chartable;
use Jdlabs\NovaMetrics\Traits\Refreshable;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Nova;

class SliderValue extends Value
{
    use Chartable, Refreshable;

    /**
     * Slide timeout in seconds
     *
     * @var int
     */
    public $slideTimeout = 5;

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'DynamicSliderMetric';
    }

    /**
     * Get the labels to be used on slider
     *
     * @param array $labels
     */
    public function labels()
    {
        return [];
    }

    /**
     * Return the slide timeout in seconds
     *
     * @param  int $slideTimeout
     * @return $this
     */
    public function slideTimeout()
    {
        return $this->slideTimeout;
    }

    /**
     * Return a partition result showing the segments of a aggregate.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string  $function
     * @param  array  $columns
     * @return \Laravel\Nova\Metrics\PartitionResult
     */
    protected function aggregate($request, $model, $function, $columns = null, $dateColumn = null)
    {
        $query = $model instanceof Builder ? $model : (new $model)->newQuery();

        $timezone = Nova::resolveUserTimezone($request) ?? $request->timezone;
        $query->whereBetween($dateColumn ?? $query->getModel()->getCreatedAtColumn(),
            $this->currentRange($request->range, $timezone));

        foreach ((array) $columns as $column) {
            if (is_string($column)) {
                $wrappedColumn = $query->getQuery()->getGrammar()->wrap(
                    $column = $column ?? $query->getModel()->getQualifiedKeyName()
                );
                $query->selectRaw(DB::raw("{$function}({$wrappedColumn}) as aggregate_{$column}"));
            } elseif (is_callable($column)) {
                $query = $column($query);
            }
        }

        $results = $query->get();

        return $this->result($results->mapWithKeys(fn($result) => $result->toArray())->all());
    }

    /**
     * Create a new partition metric result.
     *
     * @param  array  $value
     * @return \Laravel\Nova\Metrics\PartitionResult
     */
    public function result($value)
    {
        return new SliderResult($value, $this->labels());
    }

    /**
     * Prepare the metric for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'slideTimeout' => $this->slideTimeout()
        ]);
    }
}

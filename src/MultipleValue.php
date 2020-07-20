<?php

namespace Jdlabs\NovaMetrics;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Jdlabs\NovaMetrics\Traits\Filterable;
use Jdlabs\NovaMetrics\Traits\MultipleAggregatable;
use Jdlabs\NovaMetrics\Traits\Seriable;
use Laravel\Nova\Contracts\Filter as FilterContract;
use Laravel\Nova\FilterDecoder;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Nova;

class MultipleValue extends Value
{
    use Filterable, Seriable;

    /**
     * Create a new partition metric result.
     *
     * @param  array  $value
     * @return \Laravel\Nova\Metrics\PartitionResult
     */
    public function result($value)
    {
//        return new BarResult(collect($value)->map(function ($values) {
//            return array_map(function ($number) {
//                return round($number, $this->roundingPrecision, $this->roundingMode);
//            }, $values);
//        })->toArray());
        return $value;
    }

    /**
     * Return a partition result showing the segments of a aggregate.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string $function
     * @param  array  $columns
     * @param  string $dateColumn
     * @return \Laravel\Nova\Metrics\PartitionResult
     */
    protected function aggregate($request, $model, $function, $columns = null, $dateColumn = null)
    {
        $query = $model instanceof Builder ? $model : (new $model)->newQuery();
        $timezone = Nova::resolveUserTimezone($request) ?? $request->timezone;

        foreach ((array) $columns as $column) {
            if (is_string($column)) {
                $wrappedColumn = $query->getQuery()->getGrammar()->wrap(
                    $column = $column ?? $query->getModel()->getQualifiedKeyName()
                );
                $query->selectRaw(DB::raw("{$function}({$wrappedColumn}) as aggregate_{$column}"));

                // TODO: review this one
//                $previousValue = round(with(clone $query)->whereBetween(
//                    $dateColumn ?? $query->getModel()->getCreatedAtColumn(),
//                    $this->previousRange($request->range, $timezone)
//                )->{$function}($column), $this->precision);
            } elseif (is_callable($column)) {
                $query = $column($query);
            }
        }

        $results = $query->groupBy($dateColumn)->get();

        return $this->result($results->mapWithKeys(function ($result) use ($dateColumn) {
//            return round(with(clone $query)->whereBetween(
//                $dateColumn ?? $query->getModel()->getCreatedAtColumn(),
//                $this->currentRange($request->range, $timezone)
//            )->{$function}($column), $this->precision)->previous(0);
//            return $this->formatAggregateResult($result, $dateColumn);
        })->all());


//        return $this->result(
//            round(with(clone $query)->whereBetween(
//                $dateColumn ?? $query->getModel()->getCreatedAtColumn(),
//                $this->currentRange($request->range, $timezone)
//            )->{$function}($column), $this->precision)
//        )->previous($previousValue);
//
//        $query = $model instanceof Builder ? $model : (new $model)->newQuery();
//
//        $query->selectRaw($dateColumn);
//
//        foreach ((array) $columns as $column) {
//            if (is_string($column)) {
//                $wrappedColumn = $query->getQuery()->getGrammar()->wrap(
//                    $column = $column ?? $query->getModel()->getQualifiedKeyName()
//                );
//                $query->selectRaw(DB::raw("{$function}({$wrappedColumn}) as aggregate_{$column}"));
//            } elseif (is_callable($column)) {
//                $query = $column($query);
//            }
//        }
//
    }

    /**
     * Format the aggregate result for the partition.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $result
     * @param  string  $groupBy
     * @return array
     */
    protected function formatAggregateResult($result, $groupBy)
    {
        $key = $result->{last(explode('.', $groupBy))};
        $attributes = $result->getAttributes();
        unset($attributes[$groupBy]);

        dd($attributes);


        return [$key => array_values($attributes)];
    }

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'MultipleValueMetric';
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [];
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        //
    }
}

<?php


namespace Jdlabs\NovaMetrics;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Jdlabs\NovaMetrics\Traits\Seriable;
use Laravel\Nova\Metrics\Partition;

class MultipleValuesMetric extends Partition
{
    use Seriable;

    /**
     * Return a partition result showing the segments of a aggregate.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string  $function
     * @param  array  $columns
     * @param  string  $groupBy
     * @return \Laravel\Nova\Metrics\PartitionResult
     */
    protected function aggregate($request, $model, $function, $columns, $groupBy)
    {
        $query = $model instanceof Builder ? $model : (new $model)->newQuery();

        $query->selectRaw($groupBy);

        foreach ((array) $columns as $column) {
            $wrappedColumn = $query->getQuery()->getGrammar()->wrap(
                $column = $column ?? $query->getModel()->getQualifiedKeyName()
            );

            $query->selectRaw(DB::raw("{$function}({$wrappedColumn}) as aggregate_{$column}"));
        }

        $results = $query->groupBy($groupBy)->get();

        return $this->result($results->mapWithKeys(function ($result) use ($groupBy) {
            return $this->formatAggregateResult($result, $groupBy);
        })->all());
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

        return [$key => array_values($attributes)];
    }

    /**
     * Create a new partition metric result.
     *
     * @param  array  $value
     * @return \Laravel\Nova\Metrics\PartitionResult
     */
    public function result(array $value)
    {
        return new BarResult(collect($value)->map(function ($values) {
            return array_map(function ($number) {
                return round($number, $this->roundingPrecision, $this->roundingMode);
            }, $values);
        })->toArray());
    }
}

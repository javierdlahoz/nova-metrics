<?php


namespace Jdlabs\NovaMetrics;


use Cake\Chronos\Chronos;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jdlabs\NovaMetrics\MultipleValuesMetric;
use Jdlabs\NovaMetrics\Traits\Refreshable;
use Jdlabs\NovaMetrics\Traits\Seriable;
use Laravel\Nova\Metrics\Trend;
use Jdlabs\NovaMetrics\Traits\Chartable;
use Laravel\Nova\Metrics\TrendDateExpressionFactory;
use Laravel\Nova\Nova;

class TrendSeries extends Trend
{
    use Chartable, Seriable, Refreshable;

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
    public $component = 'DynamicTrendSeriesMetric';

    /**
     * Array of aggregated columns
     *
     * @var array
     */
    private $columns;

    /**
     * Return a value result showing a aggregate over time.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string  $unit
     * @param  string  $function
     * @param  string|array  $columns
     * @param  string  $dateColumn
     * @return \Laravel\Nova\Metrics\TrendResult
     */
    protected function aggregate($request, $model, $unit, $function, $columns, $dateColumn = null)
    {
        $this->columns = $columns;
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

        $query->selectRaw(DB::raw("{$expression} as date_result"));

        foreach ((array) $columns as $column) {
            if (is_string($column)) {
                $wrappedColumn = $query->getQuery()->getGrammar()->wrap($column);
                $query->selectRaw(DB::raw("{$function}({$wrappedColumn}) as aggregate_{$column}"));
            } elseif (is_callable($column)) {
                $query = $column($query);
            }
        }

        $results = $query
            ->whereBetween($dateColumn, [$startingDate, $endingDate])
            ->groupBy(DB::raw($expression))
            ->orderBy('date_result')
            ->get();

        $results = array_merge($possibleDateResults, $results->mapWithKeys(function ($result) use ($request, $unit) {
            return [$this->formatAggregateResultDate(
                $result->date_result, $unit, $request->twelveHourTime === 'true'
            ) => $this->formatAggregateResults($result)];
        })->all());

        if (count($results) > $request->range) {
            array_shift($results);
        }

        return $this->result()->trend(
            $results
        );
    }

    /**
     * Format the result
     *
     * @param  $result
     * @return array
     */
    protected function formatAggregateResults($result)
    {
        $attributes = $result->getAttributes();
        unset($attributes['date_result']);

        return array_map(function ($item) {
            return round($item, $this->precision);
        }, array_values($attributes));
    }

    /**
     * Get all of the possbile date results for the given units.
     *
     * @param  \Cake\Chronos\Chronos  $startingDate
     * @param  \Cake\Chronos\Chronos  $endingDate
     * @param  string  $unit
     * @param  mixed  $timezone
     * @param  bool  $twelveHourTime
     * @return array
     */
    protected function getAllPossibleDateResults(Chronos $startingDate, Chronos $endingDate,
                                                 $unit, $timezone, $twelveHourTime)
    {
        $nextDate = $startingDate;
        $columnCount = is_array($this->columns) ? count($this->columns) : 1;
        $emptyValue = array_fill(0, $columnCount, 0);

        if (! empty($timezone)) {
            $nextDate = $startingDate->setTimezone($timezone);
            $endingDate = $endingDate->setTimezone($timezone);
        }

        $possibleDateResults[$this->formatPossibleAggregateResultDate(
            $nextDate, $unit, $twelveHourTime
        )] = $emptyValue;

        while ($nextDate->lt($endingDate)) {
            if ($unit === self::BY_MONTHS) {
                $nextDate = $nextDate->addMonths(1);
            } elseif ($unit === self::BY_WEEKS) {
                $nextDate = $nextDate->addWeeks(1);
            } elseif ($unit === self::BY_DAYS) {
                $nextDate = $nextDate->addDays(1);
            } elseif ($unit === self::BY_HOURS) {
                $nextDate = $nextDate->addHours(1);
            } elseif ($unit === self::BY_MINUTES) {
                $nextDate = $nextDate->addMinutes(1);
            }

            if ($nextDate->lte($endingDate)) {
                $possibleDateResults[
                $this->formatPossibleAggregateResultDate(
                    $nextDate, $unit, $twelveHourTime
                )
                ] = $emptyValue;
            }
        }

        return $possibleDateResults;
    }

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
                'seriesLabels' => $this->seriesLabels(),
                'colors' => $this->colors(),
                'showMarkers' => $this->showMarkers(),
                'refreshRate' => $this->refreshRate()
            ]
        ], $this->withMeta([]));
    }
}

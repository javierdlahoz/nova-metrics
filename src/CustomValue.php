<?php

namespace Jdlabs\NovaMetrics;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Nova\Contracts\Filter as FilterContract;
use Laravel\Nova\FilterDecoder;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Nova;

class CustomValue extends Value
{
    /**
     * Custom filters from nova
     *
     * @var Illuminate\Support\Collection
     */
    private $filters;

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'CustomValueMetric';
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
    }

    /**
     * Gets the filters to be used
     *
     * @param  NovaRequest $request
     * @return Collection
     */
    protected function getFilters(NovaRequest $request) : Collection
    {
        if ($this->filters) {
            return $this->filters;
        }

        if ($request->filled('filters')) {
            $filters = (new FilterDecoder($request->input('filters')))->decodeFromBase64String();
            $filters = array_filter($filters, fn ($arr) => Arr::get($arr, 'value') != '');

            $this->filters = collect($filters);
        } else {
            $this->filters = collect([]);
        }

        return $this->filters;
    }

    /**
     * Chains filters given the query params
     *
     * @param  $query
     * @param  NovaRequest $request
     * @return mixed
     */
    public function chainFilters(Builder $query, NovaRequest $request)
    {
        $query->when($request->filled('search'),
            fn ($query) => $query = $this->applySearchFilter($query, $request)
        );

        $query->when($request->filled('trashed'),
            fn ($query) => $query = $this->applyTrashedFilter($query, $request)
        );

        $query = $this->applyFilters($query, $request);

        return $query;
    }

    /**
     * Applies the filters for metrics
     *
     * @param  $query
     * @param  NovaRequest $request
     * @return Builder
     */
    public function applyFilters(Builder $query, NovaRequest $request)
    {
        $filters = $this->getFilters($request);

        foreach ($filters as $filter) {
            $filterClass = Arr::get($filter, 'class');
            $value = Arr::get($filter, 'value');

            if (class_exists($filterClass) && class_implements($filterClass, FilterContract::class)) {
                (new $filterClass)->apply($request, $query, $value);
            } else {
                $query = $this->applyCustomFilters($query, $request, $filter);
            }
        }

        return $query;
    }

    /**
     * Applies custom filters not matching the class => value rule
     *
     * @param  Builder $query
     * @param  NovaRequest $request
     * @param  array $filter
     * @return Builder
     */
    public function applyCustomFilters(Builder $query, NovaRequest $request, $filter)
    {
        // TODO: Override this on custom metrics
        return $query;
    }

    /**
     * Applies search filter
     *
     * @param  Builder $query
     * @param  NovaRequest $request
     * @return mixed
     */
    public function applySearchFilter(Builder $query, NovaRequest $request)
    {
        $class = $request->resource();
        $searchableFields = $class::$search;
        $table = (new $class::$model)->getTable();

        $query->where(function ($query) use ($request, $searchableFields, $table) {
            foreach ($searchableFields as $field) {
                $query->orWhere($table . '.' . $field, 'like', "%{$request->input('search')}%");
            }
        });

        return $query;
    }

    /**
     * Applies trashed filter
     *
     * @param  Builder $query
     * @param  NovaRequest $request
     * @return mixed
     */
    public function applyTrashedFilter(Builder $query, NovaRequest $request)
    {
        $method = "{$request->input('trashed')}Trashed";
        $query->{$method}();
        return $query;
    }
}

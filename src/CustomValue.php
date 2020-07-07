<?php

namespace Jdlabs\NovaMetrics;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Jdlabs\NovaMetrics\Traits\Filterable;
use Laravel\Nova\Contracts\Filter as FilterContract;
use Laravel\Nova\FilterDecoder;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Nova;

class CustomValue extends Value
{
    use Filterable;

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
        //
    }
}

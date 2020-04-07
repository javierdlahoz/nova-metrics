<?php

namespace Jdlabs\NovaMetrics;

use Laravel\Nova\Metrics\Trend;

class CustomTrend extends Trend
{
    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'CustomTrendMetric';
    }
}

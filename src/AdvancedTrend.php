<?php


namespace Jdlabs\NovaMetrics;


use Jdlabs\NovaMetrics\Traits\Chartable;
use Jdlabs\NovaMetrics\Traits\Refreshable;
use Laravel\Nova\Metrics\Trend;

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
                'refreshRate' => $this->refreshRate()
            ]
        ], $this->withMeta([]));
    }
}

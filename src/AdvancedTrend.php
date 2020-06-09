<?php


namespace Jdlabs\NovaMetrics;


use Jdlabs\NovaMetrics\Traits\Chartable;
use Laravel\Nova\Metrics\Trend;

class AdvancedTrend extends Trend
{
    use Chartable;

    /**
     * Card's width
     *
     * @var string
     */
    public $width = '1/2';

    /**
     * Card's height
     *
     * @var int
     */
    public $height = 2;

    /**
     * Vue's component
     *
     * @var string
     */
    public $component = 'DynamicAdvancedTrendMetric';

    /**
     * Return the meta data to be used on the pie charts
     *
     * @return array|void
     */
    public function meta()
    {
        return [
            'meta' => [
                'cardHeight' => $this->getHeight(),
            ]
        ];
    }
}

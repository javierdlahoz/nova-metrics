<?php


namespace Jdlabs\NovaMetrics;


use Jdlabs\NovaMetrics\Traits\Chartable;
use Jdlabs\NovaMetrics\Traits\Refreshable;
use Laravel\Nova\Metrics\Partition;

class RadialChart extends Partition
{
    use Chartable, Refreshable;

    /**
     * Card's height
     *
     * @var int
     */
    public $height = 1;

    /**
     * Card's width
     *
     * @var string
     */
    public $width = '1/3';

    /**
     * Chart's component
     *
     * @var string
     */
    public $component = 'DynamicRadialMetric';

    /**
     * Label to be used in total
     *
     * @var null
     */
    public $totalLabel = null;

    /**
     * Retrieve the total to be used for calculations
     *
     * @return int|null
     */
    public function total()
    {
        return null;
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
                'colors' => $this->colors(),
                'total' => $this->total(),
                'totalLabel' => $this->totalLabel,
                'refreshRate' => $this->refreshRate()
            ]
        ], $this->withMeta([]));
    }
}

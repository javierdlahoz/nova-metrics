<?php


namespace Jdlabs\NovaMetrics;


use Illuminate\Http\Request;
use Jdlabs\NovaMetrics\Traits\Chartable;
use Jdlabs\NovaMetrics\Traits\Refreshable;
use Laravel\Nova\Card;
use Laravel\Nova\Metrics\Partition;
use Laravel\Nova\Metrics\PartitionResult;

class PieChart extends Partition
{

    use Chartable, Refreshable;

    /**
     * The width of the card (1/3, 1/2, or full).
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
     * Set if wants to display donut or not
     *
     * @var bool
     */
    public $donut = false;

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'DynamicPieChartMetric';
    }

    /**
     * Id of the chart
     *
     * @return string
     */
    protected function id()
    {
        return 'jdlabs-piechart';
    }

    /**
     * Set donut value
     *
     * @param  bool $donut
     * @return $this
     */
    public function donut(bool $donut = true)
    {
        $this->donut = $donut;
        return $this;
    }

    /**
     * Return the meta data to be used on the pie charts
     *
     * @return array|void
     */
    public function meta()
    {
        return [
            'meta' => [
                'colors' => $this->colors(),
                'cardHeight' => $this->getHeight(),
                'donut' => $this->donut,
                'refreshRate' => $this->refreshRate()
            ]
        ];
    }

}

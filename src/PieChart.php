<?php


namespace Jdlabs\NovaMetrics;


use Illuminate\Http\Request;
use Jdlabs\NovaMetrics\Traits\Chartable;
use Laravel\Nova\Card;
use Laravel\Nova\Metrics\Partition;
use Laravel\Nova\Metrics\PartitionResult;

class PieChart extends Partition
{

    use Chartable;

    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = '1/2';

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
     * Available colors
     *
     * @return string[]
     */
    protected function colors()
    {
        return [
            '#F5573B',
            '#F99037',
            '#F2CB22',
            '#8FC15D',
            '#098F56',
            '#47C1BF',
            '#1693EB',
            '#6474D7',
            '#9C6ADE',
            '#E471DE',
        ];
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
                'donut' => $this->donut
            ]
        ];
    }

}

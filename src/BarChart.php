<?php


namespace Jdlabs\NovaMetrics;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jdlabs\NovaMetrics\Traits\Chartable;
use Laravel\Nova\Card;
use Laravel\Nova\Metrics\Partition;
use Laravel\Nova\Metrics\PartitionResult;
use Laravel\Nova\Metrics\Trend;

class BarChart extends MultipleValuesMetric
{
    use Chartable;

    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = '1/3';

    /**
     * Height of the card
     *
     * @var int
     */
    public $height = 2;

    /**
     * Chart's type
     *
     * @var string
     */
    public $chartType = 'bar';

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'DynamicBarChartMetric';
    }

    /**
     * Id of the chart
     *
     * @return string
     */
    protected function id()
    {
        return 'jdlabs-barmetric';
    }

    /**
     * Available colors
     *
     * @return string[]
     */
    protected function colors()
    {
        return [
            '#25ccf7',
            '#eab543',
            '#3b3b98',
            '#bdc581',
            '#f97f51',
            '#55e6c1',
            '#f8efba',
            '#2c3a47',
            '#b33771',
            '#d6a2e8',
            '#1f8465'
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
            'meta' => array_merge([
                'colors' => $this->colors(),
                'cardHeight' => $this->getHeight(),
                'chartType' => $this->chartType
            ], $this->withMeta([]))
        ];
    }

}

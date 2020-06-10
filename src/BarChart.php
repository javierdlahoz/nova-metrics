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
                'chartType' => $this->chartType,
                'seriesLabels' => $this->seriesLabels()
            ], $this->withMeta([]))
        ];
    }

}

<?php


namespace Jdlabs\NovaMetrics;


use Jdlabs\NovaMetrics\Traits\Chartable;

class RadarChart extends BarChart
{
    use Chartable;

    /**
     * Card's height
     *
     * @var int
     */
    public $height = 2;

    /**
     * Card's width
     *
     * @var string
     */
    public $width = '1/3';

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'DynamicRadarChartMetric';
    }

    /**
     * Id of the chart
     *
     * @return string
     */
    protected function id()
    {
        return 'jdlabs-radarmetric';
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
                'seriesLabels' => $this->seriesLabels()
            ], $this->withMeta([]))
        ];
    }
}

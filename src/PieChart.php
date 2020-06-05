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
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'JdlabsPieChart';
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
        $request = request();
        return [
            'meta' => [
                'chart' => [
                    'id' => $this->id()
                ],
                'colors' => $this->colors(),
                'cardHeight' => $this->getHeight(),
                'legend' => [
                    'position' => 'left'
                ]
            ]
        ];
    }

}
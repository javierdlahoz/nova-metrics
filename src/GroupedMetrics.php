<?php


namespace Jdlabs\NovaMetrics;


use Jdlabs\NovaMetrics\Traits\Chartable;
use Laravel\Nova\Metrics\Metric;

class GroupedMetrics extends Metric
{

    use Chartable;

    /**
     * Grouped Metric Cards
     *
     * @var array
     */
    protected $cards;

    /**
     * Vue's component
     *
     * @var string
     */
    public $component = 'JdlabsGroupedMetrics';

    /**
     * @var string
     */
    public $width = '1/3';

    /**
     * GroupedMetrics constructor.
     *
     * @param array $cards
     */
    public function __construct(array $cards)
    {
        $this->cards = $cards;
        $this->height(count($cards));
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
                'cardHeight' => $this->getHeight(),
                'cards' => $this->cards
            ]
        ];
    }
}

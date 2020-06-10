<?php

namespace Jdlabs\NovaMetrics\Traits;


trait Chartable
{
    /**
     * Nova Card's height
     *
     * @var int
     */
    public static $cardHeight = 150;

    /**
     * Nova Card's offset
     *
     * @var int
     */
    public static $cardOffset = 24;

    /**
     * Get the card's height
     *
     * @return int
     */
    public function getHeight()
    {
        return ($this->height * static::$cardHeight) + (static::$cardOffset * ($this->height - 1));
    }

    /**
     * Set the height of the chart
     *
     * @param  int $height
     * @return $this
     */
    public function height($height = 1)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * Return with meta attribute
     *
     * @param  array $meta
     * @return array
     */
    public function withMeta(array $meta)
    {
        return $meta;
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
}

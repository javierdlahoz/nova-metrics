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
}

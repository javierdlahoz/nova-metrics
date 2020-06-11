<?php


namespace Jdlabs\NovaMetrics\Traits;


trait Refreshable
{

    /**
     * Refresh rate of the card, 0 = no refresh rate, 1000 = 1s, 2000 = 2s...
     *
     * @var int
     */
    public $refrehRate = 0;

    /**
     * Set the refresh rate of the card
     *
     * @return int
     */
    public function refreshRate()
    {
        return $this->refrehRate;
    }

    /**
     * Set the refresh rate
     *
     * @param  int $refreshRate
     * @return $this
     */
    public function setRefreshRate(int $refreshRate)
    {
        $this->refrehRate = $refreshRate;
        return $this;
    }
}

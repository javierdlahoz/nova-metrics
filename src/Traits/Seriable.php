<?php


namespace Jdlabs\NovaMetrics\Traits;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Jdlabs\NovaMetrics\BarResult;
use Jdlabs\NovaMetrics\TrendSeries;

trait Seriable
{
    /**
     * Series Labels
     *
     * @var array
     */
    public $seriesLabels = [];

    /**
     * Series labels
     *
     * @return array
     */
    public function seriesLabels()
    {
        return $this->seriesLabels;
    }
}

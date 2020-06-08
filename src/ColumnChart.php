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

class ColumnChart extends BarChart
{
    use Chartable;

    /**
     * Chart's type
     * 
     * @var string 
     */
    public $chartType = 'column';
}

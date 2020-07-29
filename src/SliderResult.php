<?php


namespace Jdlabs\NovaMetrics;


use Illuminate\Support\Arr;
use Laravel\Nova\Metrics\ValueResult;

class SliderResult extends ValueResult
{
    /**
     * Labels to be displayed
     *
     * @var array
     */
    public array $labels;

    /**
     * SliderResult constructor.
     * @param $value
     * @param $labels
     */
    public function __construct($value, $labels)
    {
        parent::__construct($value);
        $this->labels = $labels;
    }

    /**
     * Return the value
     */
    public function value()
    {
        $index = -1;
        return collect($this->value)->transform(function ($value, $key) use (&$index) {
            $index++;

            return [
                'value' => $value ?? '0',
                'key' => $key,
                'label' => Arr::get($this->labels, $index, $key)
            ];
        })
            ->values()
            ->toArray();
    }

    /**
     * Prepare the metric result for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'value' => $this->value(),
            'previous' => $this->previous,
            'previousLabel' => $this->previousLabel,
            'prefix' => $this->prefix,
            'suffix' => $this->suffix,
            'suffixInflection' => $this->suffixInflection,
            'format' => $this->format,
            'zeroResult' => $this->zeroResult,
        ];
    }
}

<?php


namespace Jdlabs\NovaMetrics;


use Laravel\Nova\Metrics\PartitionResult;

class BarResult extends PartitionResult
{
    /**
     * Prepare the metric result for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'value' => collect($this->value ?? [])->map(function ($value, $label) {
                return array_filter([
                    'color' => $this->colors->get($label),
                    'label' => $label,
                    'values' => $value,
                ], function ($value) {
                    return ! is_null($value);
                });
            })->values()->all(),
        ];
    }
}

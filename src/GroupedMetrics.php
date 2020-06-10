<?php


namespace Jdlabs\NovaMetrics;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\ConditionallyLoadsAttributes;
use Illuminate\Support\Facades\Log;
use Jdlabs\NovaMetrics\Traits\Chartable;
use Laravel\Nova\Card;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Metric;
use Laravel\Nova\Nova;
use Laravel\Nova\ResolvesCards;

class GroupedMetrics extends Metric
{

    use Chartable, ResolvesCards, ConditionallyLoadsAttributes;

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
        $this->cards = collect($cards);
        $this->height(count($cards));
    }

    /**
     * Get the cards available on the entity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return $this->cards
            ->filter
            ->authorize(request())
            ->values();
    }

    /**
     * Return the uriKey
     *
     * @return string
     */
    public function uriKey()
    {
        $card = $this->getCardByUriKey(request()->route('metric') ?: '');
        if ($card) {
            return $card->uriKey();
        }

        return parent::uriKey();
    }

    /**
     * calculate based on the uri key
     *
     * @param  NovaRequest $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $card = $this->getCardByUriKey($request->route('metric') ?: '');
        if ($card) {
            return $card->calculate($request);
        }
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
                'cards' => $this->cards(request())
            ]
        ];
    }

    /**
     * Return the card by uriKey
     *
     * @param  string $uri
     * @return mixed
     */
    protected function getCardByUriKey(string $uri)
    {
        return $this->cards->first(function ($card) use ($uri) {
            return $uri === $card->uriKey();
        });
    }
}

<template>
    <loading-card :loading="loading" class="px-6 py-4 advanced-trend-card" v-bind:style="{'height': `${card.meta.cardHeight}px`}">
        <div class="flex mb-4">
            <h3 class="mr-3 text-base text-80 font-bold flex-1">{{ title }}</h3>

            <div v-if="helpText" class="absolute pin-r pin-b p-2 z-50">
                <tooltip trigger="click">
                    <icon
                        type="help"
                        viewBox="0 0 17 17"
                        height="16"
                        width="16"
                        class="cursor-pointer text-60 -mb-1"
                    />

                    <tooltip-content
                        slot="content"
                        v-html="helpText"
                        :max-width="helpWidth"
                    />
                </tooltip>
            </div>

            <div @click="resetZoom()" class="flex justify-center items-center mr-2">
                <icon
                    type="search"
                    viewBox="0 0 17 17"
                    height="16"
                    width="16"
                    class="cursor-pointer text-60 -mb-1"
                />
            </div>
            <select
                v-if="ranges.length > 0"
                @change="handleChange"
                class="select-box-sm ml-auto min-w-24 h-6 text-xs appearance-none bg-40 pl-2 pr-6 active:outline-none active:shadow-outline focus:outline-none focus:shadow-outline"
            >
                <option
                    v-for="option in ranges"
                    :key="option.value"
                    :value="option.value"
                    :selected="selectedRangeKey == option.value"
                >
                    {{ option.label }}
                </option>
            </select>
        </div>

        <p class="flex items-center text-4xl mb-4 last-trend-value">
            {{ formattedValue }}
            <span v-if="suffix" class="ml-2 text-sm font-bold text-80">{{ formattedSuffix }}</span>
        </p>

        <div id="chart-timeline" class="advanced-trend" v-if="chartData && chartData.series && !reseted">
            <apexchart type="area" v-bind:height="chartHeight" ref="chart" :options="chartOptions" :series="series"></apexchart>
        </div>
    </loading-card>
</template>

<script>
import _ from 'lodash'
import { SingularOrPlural } from 'laravel-nova'
import Charteable from '../../mixins/Chartable'
import Trendable from '../../mixins/Trendable'

export default {
    name: 'AdvancedTrendMetric',

    mixins: [Charteable, Trendable],

    data() {
        return {
            reseted: false
        }
    },

    props: {
        loading: Boolean,
        title: {},
        helpText: {},
        helpWidth: {},
        value: {},
        chartData: {},
        card: {},
        maxWidth: {},
        prefix: '',
        suffix: '',
        suffixInflection: true,
        ranges: { type: Array, default: () => [] },
        selectedRangeKey: [String, Number]
    },

    methods: {
        handleChange (event) {
            this.$emit('selected', event.target.value)
        }
    },

    computed: {
        minDate() {
            let date = new Date()

            if (this.chartData.series && this.chartData.series[0]) {
                date = new Date(this.parseDate(this.chartData.series[0][0].meta))
            } else {
                date.setHours(date.getHours() - (this.selectedRangeKey + 2))
            }

            return date
        },

        maxDate() {
            let date = new Date()

            if (this.chartData.series && this.chartData.series[0]) {
                date = new Date(this.parseDate(this.chartData.series[0][this.chartData.series[0].length - 1].meta))
            }

            return date
        },

        formattedValue () {
            const value = this.chartData.series ? this.chartData.series[0][this.chartData.series[0].length - 1].value : 0
            return `${this.prefix}${value}`
        },

        formattedSuffix () {
            if (this.suffixInflection === false) {
                return this.suffix
            }

            return SingularOrPlural(this.value, this.suffix)
        },

        chartOptions () {
            return {
                chart: {
                    id: 'area-datetime',
                    type: 'area',
                    height: this.chartHeight,
                    zoom: {
                        autoScaleYaxis: true
                    },
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                markers: {
                    size: this.showMarkers ? 2 : 0,
                    style: 'solid',
                    strokeWidth: 0,
                    hover: {
                        size: 2
                    }
                },
                stroke: {
                    show: true,
                    curve: 'smooth',
                    width: 1,
                },
                xaxis: {
                    show: false,
                    type: 'datetime',
                    min: this.minDate.getTime(),
                    tickAmount: 0,
                    labels: {
                        show: false
                    },
                    axisBorder: {
                        show: false
                    }
                },
                yaxis: {
                    show: false
                },
                tooltip: {
                    x: {
                        format: 'dd MMM yyyy',
                    },
                    y: {
                        title: {
                            formatter: (serie) => 'Value'
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.9,
                        stops: [0, 100]
                    }
                },
            }
        },

        series () {
            let series = [], serie = []

            this.chartData.series.forEach(item => {
                serie = {data: []}
                item.forEach(value => {
                    serie.data.push([this.parseDate(value.meta), value.value])
                })
                series.push(serie)
            })

            return series
        },

        chartHeight() {
            return `${this.card.meta.cardHeight}px`
        },
    },
}
</script>

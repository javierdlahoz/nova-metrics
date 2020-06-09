<template>
    <loading-card :loading="loading" class="px-6 py-4" v-bind:style="{'height': `${card.meta.cardHeight}px`}">
        <div class="flex mb-4">
            <h3 class="mr-3 text-base text-80 font-bold">{{ title }}</h3>

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

        <div id="chart-timeline" v-if="chartData && chartData.series">
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
        selectedRangeKey: [String, Number],
        format: {
            type: String,
            default: '0[.]00a',
        },
    },

    watch: {
        selectedRangeKey: function (newRange, oldRange) {
            // this.renderChart()
        }
    },

    methods: {
        handleChange (event) {
            this.$emit('selected', event.target.value)
        },
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
            if (!this.isNullValue) {
                const value = numbro(new String(this.value)).format(this.format)

                return `${this.prefix}${value}`
            }

            return ''
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
                    events: {
                        beforeZoom: (info, { xaxis }) => {
                            return {
                                xaxis: {
                                    min: xaxis.min > this.minDate.getTime() ? xaxis.min : this.minDate.getTime(),
                                    max: xaxis.max < this.maxDate.getTime() ? xaxis.max : this.maxDate.getTime(),
                                }
                            }
                        }
                    },
                    toolbar: {
                        show: true,
                        tools: {
                            download: false,
                            selection: false,
                            zoom: false,
                            zoomin: true,
                            zoomout: true,
                            pan: false,
                            reset: true
                        },
                    }
                },
                annotations: {
                    yaxis: [{
                        y: 30,
                        borderColor: '#999',
                        label: {
                            show: true,
                            text: 'Support',
                            style: {
                                color: '#fff',
                                background: '#00E396'
                            }
                        }
                    }],
                    xaxis: [{
                        x: this.minDate.getTime(),
                        borderColor: '#999',
                        yAxisIndex: 0
                    }]
                },
                dataLabels: {
                    enabled: false
                },
                markers: {
                    size: 0,
                    style: 'hollow',
                },
                xaxis: {
                    type: 'datetime',
                    min: this.minDate.getTime(),
                    tickAmount: 6,
                },
                tooltip: {
                    x: {
                        format: 'dd MMM yyyy'
                    },
                    label: 'value'
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
        }
    },
}
</script>
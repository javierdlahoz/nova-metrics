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

            <div @click="resetZoom()" class="flex justify-center items-center mr-2 zoom-out">
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
        <div class="advanced-trend trend-series" v-if="chartData && chartData.series && !reseted">
            <apexchart type="area" v-bind:height="chartHeight" ref="chart" :options="chartOptions" :series="series"></apexchart>
        </div>
    </loading-card>
</template>

<script>
import _ from 'lodash'
import { SingularOrPlural } from 'laravel-nova'
import Charteable from '../../mixins/Chartable'
import InteractWithDates from '../../mixins/InteractWithDates'
import Markeable from '../../mixins/Markeable'
import Zoomable from '../../mixins/Zoomable'
import Formattable from '../../mixins/Formattable'

export default {
    mixins: [Charteable, InteractWithDates, Markeable, Zoomable, Formattable],

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

        formattedSuffix () {
            if (this.suffixInflection === false) {
                return this.suffix
            }

            return SingularOrPlural(this.value, this.suffix)
        },

        chartOptions () {
            return {
                chart: {
                    id: 'series-area-datetime',
                    type: 'area',
                    height: this.chartHeight,
                    zoom: {
                        autoScaleYaxis: true
                    },
                    toolbar: {
                        show: false
                    }
                },
                colors: this.colors,
                legend: {
                    position: 'left',
                    floating: true,
                    horizontalAlign: 'left',
                    offsetX: -20,
                    offsetY: 10,
                    formatter: (item, seriesInfo) => {
                        const index = seriesInfo.seriesIndex

                        if (this.series && this.series[index]) {
                            const value = this.formatValue(this.series[index].data[this.series[index].data.length - 1], this.format)
                            return `${this.series[index].name} ${this.prefix}${value}${this.suffix}`
                        }
                        return ''
                    }
                },
                dataLabels: {
                    enabled: false
                },
                markers: {
                    size: this.showMarkers ? 2 : 0,
                    style: 'solid',
                    strokeWidth: 0,
                    colors: this.colors,
                    hover: {
                        size: 2
                    }
                },
                stroke: {
                    show: true,
                    curve: 'straight',
                    width: 1,
                },
                xaxis: {
                    show: false,
                    type: 'datetime',
                    min: this.minDate.getTime(),
                    categories: this.categories
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
                            formatter: (serie) => serie
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    colors: this.colors,
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.9,
                        stops: [0, 100]
                    }
                },
            }
        },

        categories() {
            return _.map(this.chartData.series[0], (item) => {
                return this.parseDate(item.meta)
            })
        },

        series () {
            let series = [], serie = []
            try {
                const seriesLenght = this.chartData.series[0][0].value.length

                for (let i = 0; i < seriesLenght; i++) {
                    serie = {
                        name: this.card.meta.seriesLabels && this.card.meta.seriesLabels[i] ?
                            this.card.meta.seriesLabels[i] : `series ${i + 1}`
                    }

                    serie.data = _.map(this.chartData.series[0], (item) => {
                        return item.value[i] ? item.value[i] : 0
                    })
                    series.push(serie)
                }
            } catch ($ex) {
                return []
            }

            return series
        },

        chartHeight() {
            return `${this.card.meta.cardHeight - 30}px`
        }
    },
}
</script>

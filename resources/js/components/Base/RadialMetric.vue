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
        </div>
        <div id="chart" v-if="series && series.length > 0" style="margin-top: -24px">
            <apexchart type="radialBar" v-bind:height="chartHeight" :options="chartOptions" :series="series"></apexchart>
        </div>
    </loading-card>
</template>

<script>
import _ from 'lodash'
import Chartable from '../../mixins/Chartable'
import apexcharts from 'vue-apexcharts'

export default {
    mixins: [Chartable],

    component: [apexcharts],

    props: {
        loading: Boolean,
        title: {},
        value: {},
        card: Object,
        chartData: {},
        maxWidth: {},
    },

    methods: {
        handleChange (event) {
            this.$emit('selected', event.target.value)
        },

        getItemColor (item, index) {
            return typeof item.color === 'string' ? item.color : this.card.meta.colors[index]
        },
    },

    computed: {
        chartOptions() {
            return {
                chart: {
                    type: 'radialBar',
                    height: this.chartHeight,
                    toolbar: {
                        show: false
                    }
                },
                colors: this.colors,
                plotOptions: {
                    radialBar: {
                        startAngle: 0,
                        // endAngle: 270,
                        dataLabels: {
                            name: {
                                fontSize: this.card.meta.cardHeight > 150 ? '16px' : '11px'
                            },
                            value: {
                                fontSize: this.card.meta.cardHeight > 150 ? '14px' : '11px'
                            },
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: () => {
                                    return `${this.filteredTotal} ${this.totalLabel.toString()}`
                                }
                            }
                        }
                    },
                },
                // legend: {
                //     show: true,
                //     floating: true,
                //     fontSize: '10px',
                //     position: 'left',
                //     offsetX: 10,
                //     offsetY: 10,
                //     labels: {
                //         useSeriesColors: false,
                //     },
                // },
                labels: this.formattedLabels
            }
        },

        isNullValue() {
            return this.value == null
        },

        series() {
            return _(this.chartData).map(item => (item.value / this.filteredTotal * 100).toLocaleString('en-UK')).value()
        },

        filteredTotal() {
            let series = []
            if (!this.card.meta.total) {
                series = _(this.chartData).map(item => item.value).value()
                return _.sum(series)
            }

            return this.card.meta.total
        },

        totalLabel() {
            return this.card.meta.totalLabel
        },

        formattedLabels() {
            return _(this.chartData)
                .map(item => item.label)
                .value()
        },

        chartHeight() {
            return `${this.card.meta.cardHeight - 15}px`
        },
    },
}
</script>

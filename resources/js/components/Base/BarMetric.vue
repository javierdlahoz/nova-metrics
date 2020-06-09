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
        <div id="chart" v-if="series && series.length > 0">
            <apexchart type="bar" v-bind:height="chartHeight" :options="chartOptions" :series="series"></apexchart>
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
                    type: 'bar',
                    height: this.chartHeight,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: this.card.meta.chartType === 'bar',
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: this.formattedLabels,
                },
                yaxis: {
                    title: {
                        text: this.card.meta['y-axis'] ? this.card.meta['y-axis'] : 'Value'
                    }
                }
            }
        },

        isNullValue() {
            return this.value == null
        },

        series() {
            const series = _(this.chartData).map(item => item.values).value()
            let formattedSeries = []
            if (series[0]) {
                Array(series[0].length).fill().map((_, i) => i * i).forEach(index => {
                    formattedSeries.push({
                        name: this.card.meta.labels && this.card.meta.labels[index] ? this.card.meta.labels[index] : `serie ${index + 1}`,
                        data: _(series).map(item => item[index]).value()
                    })
                })
            }

            return formattedSeries
        },

        formattedLabels() {
            return _(this.chartData)
                .map(item => item.label)
                .value()
        }
    },
}
</script>

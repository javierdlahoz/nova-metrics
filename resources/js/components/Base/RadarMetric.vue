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
            <apexchart type="radar" v-bind:height="chartHeight" :options="chartOptions" :series="series"></apexchart>
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
                    type: 'radar',
                    height: this.chartHeight,
                    toolbar: {
                        show: false
                    },
                    dropShadow: {
                        enabled: true,
                        blur: 1,
                        left: 1,
                        top: 1
                    }
                },
                title: {
                    show: false
                },
                colors: this.colors,
                stroke: {
                    width: 2
                },
                fill: {
                    opacity: 0.1
                },
                markers: {
                    size: 0
                },
                xaxis: {
                    categories: this.card.meta.seriesLabels
                },
                labels: this.formattedLabels,
                legend: {
                    show: true,
                    position: 'bottom',
                    horizontalAlign: 'center',
                    floating: true,
                    fontSize: '10px',
                    fontFamily: 'Helvetica, Arial',
                    fontWeight: 400,
                    formatter: function (w) {
                        return w.length > 10 ? `${w.substring(0, 10)}...` : w;
                    },
                    offsetX: 0,
                    offsetY: 0
                }
            }
        },

        series() {
            let series = []

            this.chartData.forEach(item => {
                return series.push({
                    name: item.label,
                    data: item.values
                })
            })
            return series
        },

        formattedLabels() {
            return _(this.chartData)
                .map(item => item.label)
                .value()
        }
    },
}
</script>

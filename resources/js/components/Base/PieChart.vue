<template>
    <loading-card :loading="loading" class="px-6 py-4 jdlabs-pie"
                  v-bind:style="{'height': `${card.meta.cardHeight}px`}">
        <h3 class="flex mb-3 text-base text-80 font-bold">
            {{ title }}

            <span class="ml-auto font-semibold text-70 text-sm"
            >({{ formattedTotal }} {{ __('total') }})</span
            >
        </h3>

        <div v-if="helpText" class="absolute pin-r pin-b p-2">
            <tooltip trigger="hover">
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

        <div class="overflow-hidden overflow-y-auto" v-bind:style="{maxHeight: chartHeight}">
            <ul class="list-reset">
                <li
                    v-for="item in formattedItems"
                    class="text-xs text-80 leading-normal"
                >
          <span
              class="inline-block rounded-full w-2 h-2 mr-2"
              :style="{
              backgroundColor: item.color
            }"
          />{{ item.label }} ({{ item.value }} - {{ item.percentage }}%)
                </li>
            </ul>
        </div>

        <div
            ref="chart"
            :class="chartClasses"
            v-bind:style="{'height': chartHeight, 'width': chartWidth}"
            style="right: 10px; bottom: 30px; top: calc(50% + 15px);"
        />
    </loading-card>
</template>

<script>
import Chartist from 'chartist'
import 'chartist/dist/chartist.min.css'

export default {
    props: {
        loading: Boolean,
        title: String,
        helpText: {},
        helpWidth: {},
        chartData: Array,
        card: Object
    },

    data: () => ({ chartist: null }),

    watch: {
        chartData: function(newData, oldData) {
            this.renderChart()
        },
    },

    mounted() {
        console.log(this.card);
        this.chartist = new Chartist.Pie(
            this.$refs.chart,
            this.formattedChartData,
            {
                donut: this.card.meta.donut,
                donutWidth: this.donutWidth,
                donutSolid: true,
                startAngle: 270,
                showLabel: false,
            }
        )

        this.chartist.on('draw', context => {
            if (context.type === 'slice') {
                context.element.attr({
                    style: `fill: ${context.meta.color} !important`,
                })
            }
        })
    },

    methods: {
        renderChart() {
            this.chartist.update(this.formattedChartData)
        },

        getItemColor(item, index) {
            return typeof item.color === 'string' ? item.color : this.card.meta.colors[index]
        },
    },

    computed: {
        chartClasses() {
            return [
                'vertical-center',
                'rounded-b-lg',
                'ct-chart',
                'mr-4',
                this.formattedTotal <= 0 ? 'invisible' : '',
            ]
        },

        formattedChartData() {
            return { labels: this.formattedLabels, series: this.formattedData }
        },

        formattedItems() {
            return _(this.chartData)
                .map((item, index) => {
                    return {
                        label: item.label,
                        value: item.value,
                        color: this.getItemColor(item, index),
                        percentage:
                            this.formattedTotal > 0
                                ? ((item.value * 100) / this.formattedTotal).toFixed(2)
                                : '0',
                    }
                })
                .value()
        },

        formattedLabels() {
            return _(this.chartData)
                .map(item => item.label)
                .value()
        },

        formattedData() {
            return _(this.chartData)
                .map((item, index) => {
                    return {
                        value: item.value,
                        meta: { color: this.getItemColor(item, index) },
                    }
                })
                .value()
        },

        formattedTotal() {
            return _.sumBy(this.chartData, 'value')
        },

        chartHeight() {
            return this.card.meta.cardHeight === 150 ? '90px' : `${this.card.meta.cardHeight - 65}px`;
        },

        chartWidth() {
            const widths = {
                full: '50%',
                '1/2': '40%' ,
                '1/3': '90px',
                '1/4': '90px'
            };

            return widths[this.card.width];
        },

        donutWidth() {
            const widths = {
                full: 50,
                '1/2': 25,
                '1/3': 10,
                '1/4': 10
            };

            return widths[this.card.width];
        }
    },
}
</script>

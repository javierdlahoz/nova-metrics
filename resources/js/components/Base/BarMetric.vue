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

        <div
            ref="chart"
            class="absolute pin rounded-b-lg ct-chart"
            style="top: 40px"
            v-bind:style="{'height': chartHeight}"
        />
    </loading-card>
</template>

<script>
import numbro from 'numbro'
import numbroLanguages from 'numbro/dist/languages.min'

Object.values(numbroLanguages).forEach(l => numbro.registerLanguage(l))
import _ from 'lodash'
import Chartist from 'chartist'
import 'chartist-plugin-tooltips'
import 'chartist/dist/chartist.min.css'
import { SingularOrPlural } from 'laravel-nova'
import 'chartist-plugin-tooltips/dist/chartist-plugin-tooltip.css'
import Chartable from '../../mixins/Chartable'

export default {
    mixins: [Chartable],

    props: {
        loading: Boolean,
        title: {},
        helpText: {},
        helpWidth: {},
        value: {},
        card: Object,
        chartData: {},
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

    data: () => ({ chartist: null }),

    watch: {
        selectedRangeKey: function (newRange, oldRange) {
            this.renderChart()
        },

        chartData: function (newData, oldData) {
            this.renderChart()
        },
    },

    mounted () {
        if (Nova.config.locale) {
            numbro.setLanguage(Nova.config.locale.replace('_', '-'))
        }

        const low = Math.min(...this.chartData)
        const high = Math.max(...this.chartData)

        // Use zero as the graph base if the lowest value is greater than or equal to zero.
        // This avoids the awkward situation where the chart doesn't appear filled in.
        const areaBase = low >= 0 ? 0 : low

        this.formatChartData()
        this.chartist = new Chartist.Bar(this.$refs.chart, this.chartData)
    },

    methods: {
        renderChart () {
            this.formatChartData()
            this.chartist.update(this.chartData)
        },

        handleChange (event) {
            this.$emit('selected', event.target.value)
        },

        formatChartData() {
            if (this.chartData.series && this.chartData.series.length > 0) {
                let series = []
                this.chartData.series.forEach((serie) => {
                    series.push(serie.map((item) => {return item.value}))
                })
                this.chartData.series = series
            }
        }
    },

    computed: {
        isNullValue () {
            return this.value == null
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
    },
}
</script>

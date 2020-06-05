<template>
    <loading-card :loading="loading" class="flex flex-col jdlabs-card px-6 py-4"
                  v-bind:style="{height: `${card.meta.cardHeight}px`}">
        <div class="flex">
            <h3 class="mr-3 text-base text-80 font-bold">{{ card.name }}</h3>

            <span class="ml-auto font-semibold text-70 text-sm" v-if="chartData">
                ({{ formattedTotal }} {{ __('total') }})
            </span>
        </div>
        <div v-if="chartOptions && chartData && series.length > 0" class="chart-wrapper"
             v-bind:style="{height: `${card.meta.cardHeight - 40}px`}">
            <apexchart
                height="100%"
                type="pie"
                :options="chartOptions"
                :series="series"></apexchart>
        </div>
    </loading-card>
</template>
<script>
import VueApexCharts from 'vue-apexcharts'
import Chartable from '../mixins/Chartable'
import { Minimum } from 'laravel-nova'

export default {
    components: [VueApexCharts],
    mixins: [Chartable],
    props: {
        selectedRangeKey: [String, Number],
        card: {
            type: Object,
            required: true,
        },
        resourceName: {
            type: String,
            default: '',
        },
        resourceId: {
            type: [Number, String],
            default: '',
        },
    },
    mounted () {
        this.fetch()
    },
    data () {
        return {
            ready: false,
            chartData: null,
            chartOptions: {}
        }
    }
}
</script>

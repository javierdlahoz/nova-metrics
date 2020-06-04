
<template>
    <loading-card :loading="loading" class="jdlabs-card px-6 py-4">
        <div class="flex mb-4">
            <h3 class="mr-3 text-base text-80 font-bold">{{ card.name }}</h3>
        </div>
        <div v-if="chartOptions && chartData && series.length > 0" class="chart-wrapper">
            <apexchart
                width="100%"
                height="400"
                type="pie"
                :options="chartOptions"
                :series="series"></apexchart>
        </div>
    </loading-card>
</template>
<script>
    import VueApexCharts from 'vue-apexcharts';
    import Chartable from '../mixins/Chartable';
    import {Minimum} from "laravel-nova";

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
        mounted() {
            this.fetch();
        },
        data() {
            return {
                ready: false,
                chartData: null,
                chartOptions: {}
            }
        }
    };
</script>

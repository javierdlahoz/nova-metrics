<template>
    <loading-card :loading="loading" class="jdlabs-card px-6 py-4">
        <div class="flex mb-4">
            <h3 class="mr-3 text-base text-80 font-bold">{{ card.name }}</h3>
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
        <div>
            <apexchart v-if="chartOptions" width="500" type="pie" :options="chartOptions" :series="series"></apexchart>
        </div>
    </loading-card>
</template>
<script>
    export default {
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
        watch: {
            selectedRangeKey: function (newRange, oldRange) {
                this.renderChart()
            },
            card: function (newRange, oldRange) {
                this.renderChart();
            },
        },
        mounted() {
            this.ranges = this.card.ranges;
            this.renderChart();
        },
        data() {
            return {
                ready: false,
                ranges: [],
                chartOptions: {}
            }
        },
        methods: {
            renderChart() {
                if (this.card.series) {
                    this.chartOptions = this.card.meta;
                    this.chartOptions.labels = this.labels;
                }
            }
        },
        computed: {
            labels() {
                return this.card.series.map((item) => item.label);
            },
            series() {
                return this.card.series.map((item) => parseFloat(item.value));
            }
        }
    };
</script>

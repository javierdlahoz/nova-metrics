<template>
    <BasePartitionMetric
        :title="card.name"
        :help-text="card.helpText"
        :help-width="card.helpWidth"
        :chart-data="chartData"
        :loading="loading"
    />
</template>

<script>
    import { Minimum } from 'laravel-nova'
    import BasePartitionMetric from './Base/PartitionMetric'
    import MetricBehavior from './MetricBehavior'
    import Payloadable from '../mixins/Payloadable'

    export default {
        mixins: [MetricBehavior, Payloadable],

        components: {
            BasePartitionMetric,
        },

        props: {
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

            lens: {
                type: String,
                default: '',
            },
        },

        data: () => ({
            loading: true,
            chartData: [],
        }),

        watch: {
            resourceId() {
                this.fetch()
            },
        },

        created() {
            if (this.card.refreshWhenActionRuns) {
                Nova.$on("action-executed", () => this.fetch());
            }

            if (this.resourceName) {
                Nova.$on("resources-loaded", () => this.fetch());
            }
        },

        mounted() {
            if (!this.resourceName || this.card.onlyOnDetail) {
                this.fetch()
            }
        },

        methods: {
            fetch() {
                this.loading = true;
                Minimum(Nova.request().get(this.metricEndpoint, this.metricPayload)).then(
                    ({
                         data: {
                             value: { value }
                         }
                     }) => {
                        this.chartData = value;
                        this.loading = false;
                    }
                );
            }
        }
    }
</script>

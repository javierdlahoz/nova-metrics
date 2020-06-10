<template>
    <JdlabsRadialMetric
        :title="card.name"
        :help-text="card.helpText"
        :help-width="card.helpWidth"
        :chart-data="chartData"
        :loading="loading"
        :card="card"
    />
</template>

<script>
import { Minimum } from 'laravel-nova'
import JdlabsRadialMetric from './Base/RadialMetric'
import MetricBehavior from './MetricBehavior'
import Chartable from '../mixins/Chartable'

export default {
    mixins: [MetricBehavior, Chartable],

    components: {
        JdlabsRadialMetric,
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
        this.fetch();

        if (this.card.refreshWhenActionRuns) {
            Nova.$on("action-executed", () => this.fetch());
        }

        if (this.resourceName) {
            Nova.$on("resources-loaded", () => this.fetch());
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

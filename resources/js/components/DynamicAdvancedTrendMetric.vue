<template>
    <JdlabsAdvancedTrendMetric
        @selected="handleRangeSelected"
        :title="card.name"
        :help-text="card.helpText"
        :help-width="card.helpWidth"
        :value="value"
        :chart-data="data"
        :ranges="card.ranges"
        :format="format"
        :prefix="prefix"
        :suffix="suffix"
        :suffix-inflection="suffixInflection"
        :selected-range-key="selectedRangeKey"
        :loading="loading"
        :card="card"
    />
</template>

<script>
    import _ from 'lodash'
    import { InteractsWithDates, Minimum } from 'laravel-nova'
    import AdvancedTrendMetric from './Base/AdvancedTrendMetric'
    import MetricBehavior from './MetricBehavior'
    import Trendable from '../mixins/Trendable'

    export default {

        mixins: [InteractsWithDates, MetricBehavior, Trendable],

        components: {
            AdvancedTrendMetric,
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
            value: '',
            data: [],
            format: '(0[.]00a)',
            prefix: '',
            suffix: '',
            suffixInflection: true,
            selectedRangeKey: null,
        }),

        watch: {
            resourceId() {
                this.fetch()
            },
        },

        created() {
            if (this.hasRanges) {
                this.selectedRangeKey = this.card.selectedRangeKey || this.card.ranges[0].value
            }

            if (this.card.refreshWhenActionRuns) {
                Nova.$on("action-executed", () => this.fetch())
            }

            if (this.resourceName) {
                Nova.$on("resources-loaded", () => this.fetch())
            }
        },

        mounted() {
            if (!this.resourceName) {
                this.fetch()
            }
        },

        methods: {
            handleRangeSelected(key) {
                this.selectedRangeKey = key
                this.fetch()
            },
        },
    }
</script>

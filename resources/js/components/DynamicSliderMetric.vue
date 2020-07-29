<template>
    <JdlabsDynamicSliderMetric
        @selected="handleRangeSelected"
        :title="card.name"
        :help-text="card.helpText"
        :help-width="card.helpWidth"
        :previous="previous"
        :value="value"
        :ranges="card.ranges"
        :format="format"
        :prefix="prefix"
        :suffix="suffix"
        :suffix-inflection="suffixInflection"
        :selected-range-key="selectedRangeKey"
        :slide-timeout="card.slideTimeout"
        :loading="loading"
        :zero-result="zeroResult"
    />
</template>

<script>
    import { InteractsWithDates, Minimum } from 'laravel-nova'
    import JdlabsDynamicSliderMetric from './Base/DynamicSliderMetric'
    import MetricBehavior from './MetricBehavior'
    import CustomParameter from '../mixins/CustomParameter'
    import Payloadable from '../mixins/Payloadable'
    import Chartable from '../mixins/Chartable'

    export default {
        name: 'DynamicSliderMetric',

        mixins: [InteractsWithDates, MetricBehavior, CustomParameter, Payloadable, Chartable],

        components: {
            JdlabsDynamicSliderMetric,
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
            format: '(0[.]00a)',
            value: 0,
            previous: 0,
            prefix: '',
            suffix: '',
            suffixInflection: true,
            zeroResult: false,
        }),

        watch: {
            resourceId() {
                this.fetch()
            },
            '$route.query'() {
                this.$nextTick(() => this.fetch())
            }
        },

        created() {
            this.whenCreated()
        },

        mounted() {
            this.whenMounted()
        },

        computed: {
            selectedRangeKey() {
                return this.card.selectedRangeKey
            }
        }
    }
</script>

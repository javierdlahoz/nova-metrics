<script>
import Refreshable from './Refreshable'
import { Minimum } from 'laravel-nova'
import Payloadable from './Payloadable'

export default {
    mixins: [Refreshable, Payloadable],

    methods: {
        fetch () {
            this.loading = true
            Nova.request().get(this.chartEndpoint, this.metricPayload).then(
                ({
                    data: {
                        value: {
                            value,
                            previous,
                            prefix,
                            suffix,
                            suffixInflection,
                            format,
                            zeroResult,
                        },
                    },
                }) => {
                    this.value = value
                    this.format = format || this.format
                    this.prefix = prefix || this.prefix
                    this.suffix = suffix || this.suffix
                    this.suffixInflection = suffixInflection
                    this.zeroResult = zeroResult || this.zeroResult
                    this.previous = previous
                    this.loading = false
                    this.afterFetch()
                }
            )
        },

        renderChart () {
            if (this.chartData) {
                this.chartOptions = this.card.meta
                this.chartOptions.labels = this.labels
            }
        },

        handleRangeSelected(key) {
            this.selectedRangeKey = key
            this.fetch()
        },

        whenCreated() {
            if (this.hasRanges) {
                this.selectedRangeKey = this.card.ranges[0].value;
            }

            if (this.card.refreshWhenActionRuns) {
                Nova.$on("action-executed", () => this.fetch())
            }

            if (this.resourceName) {
                Nova.$on("resources-loaded", () => this.fetch())
            }
        },

        whenMounted() {
            if (!this.resourceName || this.card.onlyOnDetail) {
                this.fetch(this.selectedRangeKey)
            }
        },

        afterFetch() {
            // TODO: override in elements
        }
    },

    computed: {
        hasRanges() {
            return this.card.ranges && this.card.ranges.length > 0
        },

        chartPayload () {
            return {}
        },

        chartEndpoint () {
            if (this.resourceName && this.resourceId) {
                return `/nova-api/${this.resourceName}/${this.resourceId}/metrics/${this.card.uriKey}`
            } else if (this.resourceName) {
                return `/nova-api/${this.resourceName}/metrics/${this.card.uriKey}`
            } else {
                return `/nova-api/metrics/${this.card.uriKey}`
            }
        },

        labels () {
            return this.chartData.map((item) => item.label)
        },

        series () {
            return this.chartData.map((item) => parseFloat(item.value))
        },

        formattedTotal () {
            return this.chartData.reduce((prev, data) => prev + (data.value || 0), 0);
        },

        metricEndpoint() {
            const lens = this.lens !== '' ? `/lens/${this.lens}` : ''
            if (this.resourceName && this.resourceId) {
                return `/nova-api/${this.resourceName}${lens}/${this.resourceId}/metrics/${this.card.uriKey}`
            } else if (this.resourceName) {
                return `/nova-api/${this.resourceName}${lens}/metrics/${this.card.uriKey}`
            } else {
                return `/nova-api/metrics/${this.card.uriKey}`
            }
        },

        chartWidth() {
            const widths = {
                full: '90px',
                '1/2': '90px' ,
                '1/3': '90px',
                '1/4': '90px'
            };

            return widths[this.card.width];
        },

        chartHeight() {
            return `${this.card.meta.cardHeight - 65}px`
        },

        colors() {
            return this.card.meta.colors
        },

        resourceName() {
            return this.$route.params.resourceName
        }
    }
}
</script>

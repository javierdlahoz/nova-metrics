<script>
import { Minimum } from 'laravel-nova'

export default {
    methods: {
        fetch () {
            this.loading = true

            Minimum(Nova.request(this.chartEndpoint)).then(
                ({
                    data: {
                        value: { value },
                    },
                }) => {
                    this.chartData = value
                    this.loading = false
                    this.renderChart()
                }
            )
        },
        renderChart () {
            if (this.chartData) {
                this.chartOptions = this.card.meta
                this.chartOptions.labels = this.labels
            }
        }
    },
    computed: {
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
        }
    }
}
</script>

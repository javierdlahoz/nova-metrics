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
        },
        metricPayload() {
            let payload = { params: {} };
            if (this.resourceName) {
                const filters = this.$route.query[`${this.resourceName}_filter`];
                payload.params.filters = filters;
            }
            return payload;
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
        // chartHeight() {
        //     return this.card.meta.cardHeight === 150 ? '90px' : `${this.card.meta.cardHeight - 65}px`;
        // },
        chartWidth() {
            const widths = {
                full: '50%',
                '1/2': '40%' ,
                '1/3': '90px',
                '1/4': '90px'
            };

            return widths[this.card.width];
        },
        chartHeight() {
            return `${this.card.meta.cardHeight - 65}px`
        }
    }
}
</script>

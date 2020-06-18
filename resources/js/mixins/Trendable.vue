<script>
    import { Minimum } from 'laravel-nova'
    import _ from 'lodash'
    import Payloadable from './Payloadable'

    export default {
        mixins: [Payloadable],

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

        methods: {
            fetch() {
                this.loading = true

                Minimum(Nova.request().get(this.metricEndpoint, this.metricPayload)).then(
                    ({
                        data: {
                            value: {
                                labels,
                                trend,
                                value,
                                prefix,
                                suffix,
                                suffixInflection,
                                format,
                            },
                        },
                    }) => {
                        this.value = value
                        this.labels = Object.keys(trend)
                        this.data = {
                            labels: Object.keys(trend),
                            series: [
                                _.map(trend, (value, label) => {
                                    return {
                                        meta: label,
                                        value: value,
                                    }
                                }),
                            ],
                        }

                        this.format = format || this.format
                        this.prefix = prefix || this.prefix
                        this.suffix = suffix || this.suffix
                        this.suffixInflection = suffixInflection
                        this.loading = false
                    }
                )
            },
        },
        computed: {
            hasRanges() {
                return this.card.ranges.length > 0
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
            }
        }
    }
</script>

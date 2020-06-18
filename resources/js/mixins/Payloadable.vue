<script>
    export default {
        computed: {
            metricPayload() {
                const payload = {
                    params: {
                        timezone: this.userTimezone,
                        twelveHourTime: this.usesTwelveHourTime,
                        search: this.currentSearch
                    }
                }

                if (this.hasRanges) {
                    payload.params.range = this.selectedRangeKey
                }

                if (this.resourceName) {
                    const filters = this.encodedFilters ? this.encodedFilters : this.$route.query[`${this.resourceName}_filter`]
                    payload.params.filters = filters
                }

                return payload;
            },

            encodedFilters() {
                return this.$store.getters[`${this.resourceName}/currentEncodedFilters`]
            },

            currentSearch() {
                return this.$route.query[this.searchParameter] || ''
            },

            searchParameter() {
                return this.viaRelationship + '_search'
            }
        }
    }
</script>

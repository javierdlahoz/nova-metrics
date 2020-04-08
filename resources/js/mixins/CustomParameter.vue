<script>
    export default {
        computed: {
            parameters() {
                return {
                    search: this.currentSearch,
                    filters: this.encodedFilters,
                    trashed: this.currentTrashed
                };
            },

            currentSearch() {
                return this.$route.query[this.searchParameter] || ''
            },

            searchParameter() {
                return this.viaRelationship + '_search'
            },

            encodedFilters() {
                return this.$store.getters[`${this.resourceName}/currentEncodedFilters`]
            },

            currentTrashed() {
                return this.$route.query[this.trashedParameter] || ''
            },
        }
    }
</script>

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
                return this.$route.query[this.searchKey ? this.searchKey : this.searchParameter] || ''
            },

            searchParameter() {
                return this.viaRelationship + '_search'
            },

            encodedFilters() {
                return this.filterKey ?
                    this.$route.query[this.filterKey] :
                    this.$store.getters[`${this.resourceName}/currentEncodedFilters`]
            },

            filterKey() {
                let filterKeys = [];
                filterKeys = Object.keys(this.$route.query).filter(key => key.indexOf('_filter') >= 0);

                return filterKeys.length > 0 ? filterKeys[0] : null;
            },

            searchKey() {
                let searchKeys = [];
                searchKeys = Object.keys(this.$route.query).filter(key => key.indexOf('_search') >= 0);

                return searchKeys.length > 0 ? searchKeys[0] : null;
            },

            trashedKey() {
                let trashedKeys = [];
                trashedKeys = Object.keys(this.$route.query).filter(key => key.indexOf('_trashed') >= 0);

                return trashedKeys.length > 0 ? trashedKeys[0] : null;
            },

            currentTrashed() {
                return this.$route.query[this.trashedKey ? this.trashedKey : this.trashedParameter] || ''
            },
        }
    }
</script>

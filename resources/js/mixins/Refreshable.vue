<script>
export default {
    data() {
        return {
            refreshTimeout: null
        }
    },

    created() {
        if (this.isRefreshable) {
            this.refresh()
        }
    },

    beforeDestroy() {
        clearTimeout(this.refreshTimeout)
    },

    methods: {
        refresh() {
            this.refreshTimeout = setTimeout(() => {
                this.fetch()
                this.refresh()
            }, this.refreshRate)
        }
    },

    computed: {
        isRefreshable() {
            return this.refreshRate && this.refreshRate > 0
        },

        refreshRate() {
            return this.card.meta.refreshRate
        }
    }
}
</script>

<script>
export default {
    methods: {
        resetZoom() {
            this.reseted = true

            setTimeout(() => {
                this.reseted = false
            }, 50)
        },
    }
}
</script>

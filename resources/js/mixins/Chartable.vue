<script>
    import {Minimum} from "laravel-nova";

    export default {
        computed: {
            chartPayload() {
                return {};
            },
            chartEndpoint() {
                if (this.resourceName && this.resourceId) {
                    return `/nova-api/${this.resourceName}/${this.resourceId}/metrics/${this.card.uriKey}`
                } else if (this.resourceName) {
                    return `/nova-api/${this.resourceName}/metrics/${this.card.uriKey}`
                } else {
                    return `/nova-api/metrics/${this.card.uriKey}`
                }
            }
        }
    }
</script>

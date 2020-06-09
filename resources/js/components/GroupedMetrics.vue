<template>
    <div v-if="cards.length > 0" class="grouped-cards-wrapper">
        <cards :cards="cards" class="mb-3" size="large" />
    </div>
</template>

<script>
import Chartable from '../mixins/Chartable'

export default {
    mixins: [Chartable],

    props: {
        card: {
            type: Object,
            required: true,
        }
    },

    computed: {
        cards() {
            return this.card.meta.cards
        }
    }
}
</script>

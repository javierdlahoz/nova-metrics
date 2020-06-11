<script>
import numbro from 'numbro'
import numbroLanguages from 'numbro/dist/languages.min'
Object.values(numbroLanguages).forEach(l => numbro.registerLanguage(l))

export default {
    mounted() {
        if (Nova.config.locale) {
            numbro.setLanguage(Nova.config.locale.replace('_', '-'))
        }
    },

    methods: {
        formatValue(value, format) {
            return numbro(new String(value)).format(format)
        }
    }
}
</script>

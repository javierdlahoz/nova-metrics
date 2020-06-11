<script>
export default {
    methods: {
        parseDate(date) {
            const year = (new Date()).getFullYear(), splitDate = date.split(' - ')
            let formattedDate = date

            if (splitDate.length > 1) {
                formattedDate = `${splitDate[0]}, ${year.toString()} ${splitDate[1]}`
            }

            return Date.parse(formattedDate)
        }
    }
}
</script>

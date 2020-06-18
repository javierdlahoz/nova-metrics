<script>
export default {
    methods: {
        parseDate(date) {
            let dateObject, formattedDate = date
            const year = (new Date()).getFullYear(), splitDate = date.split(' - ')
            
            if (splitDate.length > 1) {
                formattedDate = `${splitDate[0]}, ${year.toString()} ${splitDate[1]}`
            }

            dateObject = new Date(Date.parse(formattedDate))
            dateObject.setDate(dateObject.getDate() + 1)

            return dateObject.valueOf()
        }
    }
}
</script>

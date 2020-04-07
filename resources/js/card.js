Nova.booting((Vue, router, store) => {
  Vue.component('CustomTrendMetric', require('./components/CustomTrendMetric').default)
  Vue.component('CustomValueMetric', require('./components/CustomValueMetric').default)
  Vue.component('CustomPartitionMetric', require('./components/CustomPartitionMetric').default)
})

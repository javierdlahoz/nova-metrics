Nova.booting((Vue, router, store) => {

    Vue.component('CustomTrendMetric', require('./components/CustomTrendMetric').default);
    Vue.component('CustomValueMetric', require('./components/CustomValueMetric').default);
    Vue.component('CustomPartitionMetric', require('./components/CustomPartitionMetric').default)

    Vue.component('CustomBaseValueMetric', require('./components/Base/ValueMetric').default);
    Vue.component('CustomBasePartitionMetric', require('./components/Base/PartitionMetric').default);
    Vue.component('CustomBaseTrendMetric', require('./components/Base/TrendMetric').default);
})

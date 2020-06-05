Nova.booting((Vue, router, store) => {
    Vue.component('CustomTrendMetric', require('./components/CustomTrendMetric').default);
    Vue.component('CustomValueMetric', require('./components/CustomValueMetric').default);
    Vue.component('CustomPartitionMetric', require('./components/CustomPartitionMetric').default)
    Vue.component('DynamicPieChartMetric', require('./components/DynamicPieChart').default)

    Vue.component('JdlabsPieChart', require('./components/Base/PieChart').default);
    Vue.component('CustomBaseValueMetric', require('./components/Base/ValueMetric').default);
    Vue.component('CustomBasePartitionMetric', require('./components/Base/PartitionMetric').default);
    Vue.component('CustomBaseTrendMetric', require('./components/Base/TrendMetric').default);

    // Vue.component('JdlabsBarChart', require('./components/BarChart').default);

    Vue.mixin(require('./mixins/CustomParameter'));
})

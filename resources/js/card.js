// TODO: improve the component naming here

Nova.booting((Vue, router, store) => {
    Vue.component('CustomTrendMetric', require('./components/CustomTrendMetric').default)
    Vue.component('CustomValueMetric', require('./components/CustomValueMetric').default)
    Vue.component('CustomPartitionMetric', require('./components/CustomPartitionMetric').default)
    Vue.component('DynamicPieChartMetric', require('./components/DynamicPieChart').default)
    Vue.component('DynamicBarChartMetric', require('./components/DynamicBarChart').default)
    Vue.component('DynamicRadarChartMetric', require('./components/DynamicRadarChart').default)
    Vue.component('DynamicAdvancedTrendMetric', require('./components/DynamicAdvancedTrendMetric').default)
    Vue.component('DynamicTrendSeriesMetric', require('./components/DynamicTrendSeriesMetric').default)
    Vue.component('DynamicRadialMetric', require('./components/DynamicRadialMetric').default)
    Vue.component('JdlabsGroupedMetrics', require('./components/GroupedMetrics').default)

    Vue.component('JdlabsPieMetric', require('./components/Base/PieMetric').default)
    Vue.component('JdlabsBarMetric', require('./components/Base/BarMetric').default)
    Vue.component('JdlabsRadialMetric', require('./components/Base/RadialMetric').default)
    Vue.component('JdlabsAdvancedTrendMetric', require('./components/Base/AdvancedTrendMetric').default)
    Vue.component('JdlabsTrendSeriesMetric', require('./components/Base/TrendSeriesMetric').default)
    Vue.component('JdlabsRadarMetric', require('./components/Base/RadarMetric').default)

    Vue.component('CustomBaseValueMetric', require('./components/Base/ValueMetric').default)
    Vue.component('CustomBasePartitionMetric', require('./components/Base/PartitionMetric').default)
    Vue.component('CustomBaseTrendMetric', require('./components/Base/TrendMetric').default)

    // Vue.component('JdlabsBarChart', require('./components/BarChart').default)

    Vue.mixin(require('./mixins/CustomParameter'))
})

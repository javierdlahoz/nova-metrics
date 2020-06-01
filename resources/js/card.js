import VueApexCharts from 'vue-apexcharts';

Nova.booting((Vue, router, store) => {
    try {
        Vue.use(VueApexCharts);
        Vue.component('apexchart', VueApexCharts);
    } catch (e) {
        console.log('apexcharts already loaded');
    }


    Vue.component('CustomTrendMetric', require('./components/CustomTrendMetric').default);
    Vue.component('CustomValueMetric', require('./components/CustomValueMetric').default);
    Vue.component('CustomPartitionMetric', require('./components/CustomPartitionMetric').default)

    Vue.component('CustomBaseValueMetric', require('./components/Base/ValueMetric').default);
    Vue.component('CustomBasePartitionMetric', require('./components/Base/PartitionMetric').default);
    Vue.component('CustomBaseTrendMetric', require('./components/Base/TrendMetric').default);

    Vue.component('JdlabsPieChart', require('./components/PieChart').default);

    Vue.mixin(require('./mixins/CustomParameter'));
})

<template>
  <div :class="className" :style="{height:height,width:width}" />
</template>

<script>
import echarts from 'echarts';
require('echarts/theme/macarons'); // echarts theme
import { debounce } from '@/utils';

export default {
  props: {
    className: {
      type: String,
      default: 'chart',
    },
    width: {
      type: String,
      default: '100%',
    },
    height: {
      type: String,
      default: '350px',
    },
    autoResize: {
      type: Boolean,
      default: true,
    },
    chartData: {
      type: Object,
      required: true,
    },
    xAxisData: {
      type: Array,
      default: () => {},
    },
    seriesTitle: {
      type: Object,
      default: () => {},
    },
  },
  data() {
    return {
      chart: null,
      sidebarElm: null,
    };
  },
  watch: {
    chartData: {
      deep: true,
      handler(val) {
        this.setOptions(val);
      },
    },
  },
  mounted() {
    this.initChart();
    if (this.autoResize) {
      this.__resizeHandler = debounce(() => {
        if (this.chart) {
          this.chart.resize();
        }
      }, 100);
      window.addEventListener('resize', this.__resizeHandler);
    }

    // Monitor the sidebar changes
    this.sidebarElm = document.getElementsByClassName('sidebar-container')[0];
    this.sidebarElm && this.sidebarElm.addEventListener('transitionend', this.sidebarResizeHandler);
  },
  beforeDestroy() {
    if (!this.chart) {
      return;
    }
    if (this.autoResize) {
      window.removeEventListener('resize', this.__resizeHandler);
    }

    this.sidebarElm && this.sidebarElm.removeEventListener('transitionend', this.sidebarResizeHandler);

    this.chart.dispose();
    this.chart = null;
  },
  methods: {
    sidebarResizeHandler(e) {
      if (e.propertyName === 'width') {
        this.__resizeHandler();
      }
    },
    setOptions({ expectedData, actualData } = {}) {
      this.chart.setOption({
        xAxis: {
          data: this.xAxisData,
          boundaryGap: false,
          axisTick: {
            show: false,
          },
        },
        grid: {
          left: 10,
          right: 10,
          bottom: 40,
          top: 30,
          containLabel: true,
        },
        tooltip: {
          trigger: 'axis',
          axisPointer: {
            type: 'cross',
          },
          padding: [5, 10],
        },
        yAxis: {
          name: this.$t('chart.nameYAxis'),
          nameTextStyle: {
            align: 'left',
            color: '#e33364',
            fontSize: '14',
            fontWeight: '500',
            fontFamily: 'sans-serif',
          },
          axisTick: {
            show: false,
          },
        },
        toolbox: {
          left: 'right',
          feature: {
            dataZoom: {
              title: {
                zoom: this.$t('chart.zoomIn'),
                back: this.$t('chart.zoomOut'),
              },
              yAxisIndex: 'none',
            },
            restore: {
              title: this.$t('chart.restoreConfig'),
            },
            saveAsImage: {
              title: this.$t('chart.saveAsImage'),
              name: 'kilo',
            },
          },
          tooltip: {
            show: true,
          },
        },
        dataZoom: [{
          startValue: this.xAxisData[0],
          endValue: this.xAxisData[this.xAxisData.length],
        }, {
          type: 'inside',
        }],
        legend: {
          data: ['expected', 'actual'],
        },
        series: [
          {
            name: 'actual',
            smooth: true,
            type: 'line',
            itemStyle: {
              normal: {
                color: '#3888fa',
                lineStyle: {
                  color: '#3888fa',
                  width: 2,
                },
                areaStyle: {
                  color: '#f3f8ff',
                },
              },
            },
            data: actualData,
            animationDuration: 2800,
            animationEasing: 'quadraticOut',
          },
        ],
      });
    },
    initChart() {
      this.chart = echarts.init(this.$el, 'macarons');
      this.setOptions(this.chartData);
    },
  },
};
</script>

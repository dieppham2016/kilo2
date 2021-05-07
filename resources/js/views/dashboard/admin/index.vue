<template>
  <div class="dashboard-editor-container">

    <panel-group :time-line="timeLine" :total-bill="total" />

    <el-row>
      <el-radio-group v-model="timeLine">
        <el-radio-button label="lastWeek">{{ $t('chart.lastWeek') }}</el-radio-button>
        <el-radio-button label="lastMonth">{{ $t('chart.lastMonth') }}</el-radio-button>
        <el-radio-button label="last3Month">{{ $t('chart.last3Month') }}</el-radio-button>
        <el-radio-button label="thisMonth">{{ $t('chart.thisMonth') }}</el-radio-button>
        <el-radio-button label="allTime">{{ $t('chart.allTime') }}</el-radio-button>
      </el-radio-group>
    </el-row>
    <el-row style="background:#fff;padding:16px 16px 0;margin-bottom:32px;">
      <line-chart v-loading="chartLoading" :x-axis-data="xAxisData" :chart-data="chartData" />
    </el-row>

  </div>
</template>

<script>
import PanelGroup from './components/PanelGroup';
import LineChart from './components/LineChart';
// import Resource from '@/api/resource';

const DateFormat = require('dateformat');

export default {
  name: 'DashboardAdmin',
  components: {
    PanelGroup,
    LineChart,
  },
  data() {
    return {
      total: 0,
      timeLine: 'lastWeek',
      chartData: {},
      chartLoading: false,
      xAxisData: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
      dateFormat: 'yyyy-mm-dd HH:MM:ss',
    };
  },
  watch: {
    timeLine(time) {
      this.handleSelectTime(time);
    },
  },
  created() {
    this.handleSelectTime(this.timeLine);
  },
  methods: {
    handleSelectTime(time) {
      // if (time === 'lastWeek') {
      //   this.getBillByTimeLine(this.getLastWeek());
      // } else if (time === 'lastMonth') {
      //   this.getBillByTimeLine(this.getLastMonth());
      // } else if (time === 'last3Month') {
      //   this.getBillByTimeLine(this.getLast3Month());
      // } else if (time === 'thisMonth') {
      //   this.getBillByTimeLine(this.getThisMonth());
      // } else if (time === 'allTime') {
      //   this.getBillByTimeLine();
      // }
    },
    // handleSetLineChartData(type) {
    //   this.lineChartData = this.lineChartData[type];
    // },
    getLastWeek() {
      const today = new Date();
      const mondayOfWeek = new Date(today.setDate(today.getDate() - (today.getDay() + 6) % 7));
      const mondayOfLastWeek = new Date(mondayOfWeek.setDate(mondayOfWeek.getDate() - 7));
      const date = [];
      date[0] = DateFormat(new Date(mondayOfLastWeek.getFullYear(), mondayOfLastWeek.getMonth(), mondayOfLastWeek.getDate(), 0, 0, 0), this.dateFormat);
      date[1] = DateFormat(new Date(mondayOfLastWeek.getFullYear(), mondayOfLastWeek.getMonth(), mondayOfLastWeek.getDate() + 6, 23, 59, 59), this.dateFormat);
      return date;
    },
    getThisMonth() {
      const today = new Date();
      const date = [];
      date[0] = DateFormat(new Date(today.getFullYear(), today.getMonth(), 1), this.dateFormat);
      date[1] = DateFormat(new Date(today.getFullYear(), today.getMonth(), today.getDate(), 23, 59, 59), this.dateFormat);
      return date;
    },
    getLastMonth() {
      const today = new Date();
      const month = new Date(today.setMonth(today.getMonth(), 0));
      const date = [];
      date[0] = DateFormat(new Date(month.getFullYear(), month.getMonth() + 1, 1 - month.getDate()), this.dateFormat);
      date[1] = DateFormat(new Date(month.getFullYear(), month.getMonth(), month.getDate(), 23, 59, 59), this.dateFormat);
      return date;
    },
    getLast3Month() {
      const today = new Date();
      const month = new Date(today.setMonth(today.getMonth(), 0));
      const date = [];
      date[0] = DateFormat(new Date(month.getFullYear(), month.getMonth() - 2, 1), this.dateFormat);
      date[1] = DateFormat(new Date(month.getFullYear(), month.getMonth(), month.getDate(), 23, 59, 59), this.dateFormat);
      return date;
    },
    // async getBillByTimeLine(timeLine) {
    //   this.chartLoading = true;
    //   // const { data, meta } = await BillResource.index({ date: timeLine });
    //   // const axisData = this.getXAxisData(data);
    //   // this.xAxisData = axisData.xAxis;
    //   // this.chartData = { actualData: axisData.count };
    //   // this.total = meta.total;
    //   this.chartLoading = false;
    // },
    getXAxisData(data) {
      data.sort(function(a, b) {
        const dateA = new Date(a.created_at), dateB = new Date(b.created_at);
        return dateA - dateB;
      });
      const groups = data.reduce((groups, bill) => {
        const date = bill.created_at.split('T')[0];
        if (!groups[date]) {
          groups[date] = [];
        }
        groups[date].push(bill);
        return groups;
      }, {});

      const groupArrays = Object.keys(groups).map((date) => {
        return {
          date,
          bills: groups[date],
        };
      });

      return {
        xAxis: groupArrays.map(x => {
          return DateFormat(x.date, 'dd-mm-yyyy');
        }),
        count: groupArrays.map(x => {
          return x.bills.length;
        }),
      };
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
.dashboard-editor-container {
  padding: 32px;
  background-color: rgb(240, 242, 245);
  .chart-wrapper {
    background: #fff;
    padding: 16px 16px 0;
    margin-bottom: 32px;
  }
}
</style>

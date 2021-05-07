<template>
  <el-dropdown :hide-on-click="false" :show-timeout="100" trigger="click">
    <el-button plain>
      {{ showDate !== null ? showDate : $t('actions.pickDate') }}
      <i class="el-icon-caret-bottom el-icon--right" />
    </el-button>

    <el-dropdown-menu slot="dropdown" class="no-border">
      <el-date-picker
        v-model="dateValue"
        type="daterange"
        align="right"
        unlink-panels
        value-format="yyyy-MM-dd"
        range-separator="To"
        start-placeholder="Start date"
        end-placeholder="End date"
        :picker-options="options"
        @change="getDate"
      />
    </el-dropdown-menu>
  </el-dropdown>
</template>

<script>
export default {
  props: {
    value: {
      default: () => [],
      type: Array,
    },
  },
  data() {
    return {
      showDate: null,
      options: {
        shortcuts: [{
          text: 'Hôm nay',
          onClick(picker) {
            const time = new Date();
            picker.$emit('pick', [time, time]);
            this.showDate = time;
          },
        }, {
          text: 'Tuần trước',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
            picker.$emit('pick', [start, end]);
          },
        }, {
          text: 'Tháng trước',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
            picker.$emit('pick', [start, end]);
          },
        }, {
          text: '3 tháng cuối',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
            picker.$emit('pick', [start, end]);
          },
        }],
      },
    };
  },
  computed: {
    dateValue: {
      get() {
        return this.value;
      },
      set(val) {
        this.$emit('input', val);
      },
    },
  },
  methods: {
    getDate(date) {
      if (date !== null) {
        if (date[0] === date[1]) {
          this.showDate = date[0];
        } else {
          this.showDate = date[0] + ' To ' + date[1];
        }
      } else {
        this.showDate = null;
      }
    },
  },
};
</script>

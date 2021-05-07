<template>
  <el-dropdown :hide-on-click="false" :show-timeout="100" trigger="click">
    <el-button plain>
      {{ showDate !== null ? showDate : 'Thời gian' }}
      <i class="el-icon-caret-bottom el-icon--right" />
    </el-button>

    <el-dropdown-menu slot="dropdown" class="no-border">
      <el-time-picker
        v-model="timeValue"
        is-range
        range-separator="To"
        start-placeholder="Start time"
        end-placeholder="End time"
        value-format="HH:mm:ss"
        @change="getDate"
      />
    </el-dropdown-menu>
  </el-dropdown>
</template>

<script>
export default {
  props: {
    value: {
      required: true,
      default: () => [],
      type: Array,
    },
  },
  data() {
    return {
      timeValue: null,
      showDate: null,
    };
  },
  computed: {
    status: {
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

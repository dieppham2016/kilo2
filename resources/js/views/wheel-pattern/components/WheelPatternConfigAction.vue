<template>
  <el-row :class="bottomLine ? 'bottom-line' : ''" type="flex" justify="end" align="middle">
    <el-button align="right" icon="el-icon-close" type="danger" @click="handleClose">
      {{ $t('wheel.pattern.table.actions.close') }}
    </el-button>
    <el-button align="right" :icon="mainIcon" type="info" @click="handleUpdate">
      {{ $t('wheel.pattern.table.actions.' + action) }}
    </el-button>
  </el-row>
</template>

<script>
export default {
  name: 'WheelPatternConfigAction',
  props: {
    action: {
      type: String,
      default: 'create',
    },
    bottomLine: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      mainIcon: '',
    };
  },
  mounted() {
    this.mainIcon = this.action === 'create' ? 'el-icon-plus' : 'el-icon-refresh';
  },
  methods: {
    handleUpdate() {
      this.$emit('handleUpdate');
    },
    handleClose() {
      this.$emit('handleClose');
    },
  },
};
</script>

<style scoped>
.bottom-line {
  border-bottom: 1px solid #00aaff;
  margin-bottom: 15px;
  padding-bottom: 15px
}
</style>

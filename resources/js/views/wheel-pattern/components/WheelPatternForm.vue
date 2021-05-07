<template>
  <el-dialog custom-class="wheel-pattern-form-dialog" :show-close="false" :visible.sync="visible" :before-close="onCancel">
    <wheel-pattern-config :pattern="pattern" @onClose="handleClose" />
  </el-dialog>
</template>

<script>

import WheelPatternConfig from '@/views/wheel-pattern/components/WheelPatternConfig';
export default {
  name: 'WheelPatternForm',
  components: { WheelPatternConfig },
  props: {
    pattern: {
      type: Object,
      default: () => null,
    },
    dialogWidth: {
      type: String,
      default: '400px',
    },
    visible: {
      type: Boolean,
      default: false,
    },
    customStyle: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      isLoading: false,
      formName: '',
    };
  },
  created() {
    this.formName = this.prize ? this.$t('form.title.edit') : this.$t('form.title.create');
  },
  methods: {
    handleClose() {
      this.onClose();
    },
    onClose() {
      this.$emit('onClose', false);
      this.isLoading = false;
    },
    onCancel() {
      this.onClose();
    },
    onConfirm() {},
  },
};
</script>

<style lang="scss">
.wheel-pattern-form-dialog {
  .el-dialog__header {
    display: none;
  }
  .el-dialog__body {
    padding: 5px;
    //background: #b0b0b0;
    background: linear-gradient(#2aa2da, #0af, #57bbe5);
  }
  .el-dialog__footer {
    display: none;
  }
}
</style>

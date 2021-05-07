<template>
  <!--  <el-dialog v-if="isShowWinningDialog" custom-class="prize-dialog-wrapper" :show-close="false" center visible>-->
  <el-dialog custom-class="prize-dialog-wrapper" :show-close="false" center :visible="isShowWinningDialog" @closed="handleClose">
    <el-row class="prize-dialog" type="flex" justify="center" align="middle">
      <div class="prize-dialog-content">Chúc mừng bé nhận được: <span class="prize-dialog-item">{{ prizeWinning }}</span></div>
    </el-row>
  </el-dialog>
</template>

<script>
export default {
  name: 'WinPrizeDialog',
  props: {
    prizeWinning: {
      type: String,
      default: '',
    },
    isShowWinningDialog: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
    };
  },
  methods: {
    handleClose() {
      this.$emit('onClosed');
    },
  },
};
</script>

<style lang="scss">
.prize-dialog-wrapper {
  border-radius: 1rem!important;
  .el-dialog__header{
    padding: 0;
    border-radius: 1rem;
  }
  .el-dialog__body {
    padding: 0;
    border-radius: 1rem;background: rgb(127,17,217);
    background: linear-gradient(90deg, rgba(127,17,217,1) 0%, rgba(134,4,177,1) 36%, rgba(161,6,155,1) 66%, rgba(219,7,222,1) 100%);
    .prize-dialog-content{
      color: #EFEFEF;
      font-size: 2rem;
      .prize-dialog-item {
        text-shadow: 2px 2px 5px #0fd8f4;
      }
    }
  }
}
</style>

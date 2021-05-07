<template>
  <div>
    <!--    <el-dialog v-if="isShowDemoDialog" custom-class="demo-dialog&#45;&#45;wrapper" fullscreen visible :show-close="false" center @closed="handleClose">-->
    <el-dialog custom-class="demo-dialog--wrapper" fullscreen :visible="isShowDemoDialog" :show-close="false" center @closed="handleClose">
      <div class="demo-dialog--bottom">
        <el-row type="flex" justify="center" align="bottom">
          <el-col :span="8" :style="{visibility: ticket > 0 ? 'visible' : 'hidden'}">
            <el-row type="flex" align="bottom" justify="left">
              <span class="demo--credit">Tickets: </span>
              <span class="demo--credit">{{ ticket }}</span>
            </el-row>
            <span :style="{visibility: error ? 'visible' : 'hidden'}" class="demo--credit">ERROR: {{ error }}</span>
          </el-col>
          <el-col :span="8">
            <el-row type="flex" justify="center" align="middle">
              <span class="demo--suggest">Swipe cards to play game</span>
            </el-row>
            <el-row type="flex" justify="center" align="middle">
              <!--              <el-button class="demo&#45;&#45;button" type="success" @click="handleAddCoin">Test Game</el-button>-->
            </el-row>
            <el-row type="flex" justify="center" align="middle">
              <span class="demo--credit">Credits: {{ credit }}/1</span>
            </el-row>
          </el-col>
          <el-col :span="8" />
        </el-row>
      </div>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name: 'DemoDialog',
  props: {
    isShowDemoDialog: {
      type: Boolean,
      default: false,
    },
    credit: {
      type: Number,
      default: 0,
    },
    ticket: {
      type: Number,
      default: 0,
    },
    error: {
      type: String,
      default: '',
    },
  },
  data() {
    return {

    };
  },
  methods: {
    handleAddCoin() {
      this.$emit('addCoin');
    },
    handleClose() {
      this.$emit('onClose');
    },
  },
};
</script>

<style lang="scss">
.demo-dialog--wrapper {
  .el-dialog__header{
    display: none;
  }
  .el-dialog__body{
    height: 100vh;
    background: #0a76a4 url("../../../assets/demo.png") no-repeat;
    background-size: cover;
  }

  .demo-dialog--bottom {
    width: 100%;
    position: absolute;
    padding: 20px 0;
    bottom: 0;
    left: 0;

    .demo--button {
      box-shadow: 0px 1px 3px 1px #464848;
      text-shadow: 1px 1px 5px #3db2ed;
    }
    .demo--credit {
      user-select: none;
      margin-top: 20px;
      font-size: 2rem;
      color: #efefef;
      font-style: italic;
      text-shadow: 1px 1px 5px #cca336, 1px 1px 10px #c6821b,  -1px -1px 10px #d213b2;
    }

    .demo--suggest {
      user-select: none;
      margin-bottom: 20px;
      font-size: 2rem;
      color: #efefef;
      font-style: italic;
      text-shadow: 1px 1px 5px #00aaff, 1px 1px 10px #023d5b,  -1px -1px 10px #00aaff;
      animation: gold .5s infinite alternate;
    }
    @keyframes gold {
      to {
        text-shadow: 1px 1px 5px #cca336, 1px 1px 10px #ae920e,  -1px -1px 10px #d8ce79;
      }
    }
  }
}
</style>

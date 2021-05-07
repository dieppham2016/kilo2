<template>
  <div class="wheel-info">
    <el-row type="flex" justify="left">
      <el-col>
        <div class="wheel-bonus">
          <el-row type="flex" justify="center" align="middle"><span class="wheel-label"> Time: </span></el-row>
          <el-row type="flex" justify="center" align="middle"><span
            class="wheel-bonus--current"
          >{{ timePlay }}</span></el-row>
        </div>
      </el-col>
      <el-col>
        <div v-if="bonusEnable" class="wheel-bonus">
          <el-row type="flex" justify="center" align="middle">
            <span class="wheel-label"> Bonus: </span>
          </el-row>
          <el-row type="flex" justify="center" align="middle">
            <span class="wheel-bonus--current">{{ currentBonus }}</span>
          </el-row>
        </div>
      </el-col>
    </el-row>
  </div>

</template>

<script>
export default {
  name: 'TopBar',
  props: {
    timePlay: {
      type: Number,
      default: 0,
    },
    currentBonus: {
      type: Number,
      default: 0,
    },
    bonusEnable: {
      type: Boolean,
      default: true,
    },
  },
};
</script>

<style lang="scss">

.wheel-info {
  margin-top: 1rem;
  width: 100%;
  height: 7rem;
  position: absolute;
  top: 0;
  left: 0;
  background: transparent;

  .wheel-bonus {
    width: 7rem;
    height: 7rem;
    margin: auto;
    background: #0af;
    font-size: 2.25rem;
    padding: 1rem;
    color: #EFEFEF;
    border-radius: 3.5rem;
  }

  .wheel-label {
    font-size: 1.25rem;
    text-align: center;
  }
}

</style>

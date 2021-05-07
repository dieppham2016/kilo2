<template>
  <div class="wheel-bottom">
    <el-row type="flex" align="bottom">
      <el-col :span="8" :style="{visibility: ticket > 0 ? 'visible' : 'hidden'}">
        <el-row type="flex" align="bottom" justify="left">
          <span class="wheel-credit">Tickets: </span>
          <span class="wheel-credit">{{ ticket }}</span>
        </el-row>
        <span :style="{visibility: error ? 'visible' : 'hidden'}" class="wheel-credit">ERROR: {{ error }}</span>
      </el-col>
      <el-col :span="8">
        <el-row type="flex" justify="center" align="bottom">
          <button class="wheel-bottom--btn-start" @click="startPin()">Start</button>
        </el-row>
      </el-col>
    </el-row>
  </div>
</template>
<script>
export default {
  name: 'BottomBar',
  props: {
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
  methods: {
    startPin() {
      this.$emit('handleStart');
    },
  },
};
</script>

<style lang="scss">

.wheel-bottom {
  width: 100%;
  height: auto;
  position: absolute;
  bottom: 0;
  left: 0;
  background: transparent;

  .wheel-credit {
    user-select: none;
    margin-bottom: 20px;
    font-size: 2rem;
    color: #efefef;
    font-style: italic;
    text-shadow: 1px 1px 5px #cca336, 1px 1px 10px #c6821b, -1px -1px 10px #d213b2;
  }

  .wheel-bottom--btn-start {
    font-family: 'Nunito', sans-serif;
    color: #aa0f35;
    margin-bottom: 2rem;
    text-transform: uppercase;
    letter-spacing: 1.3px;
    font-weight: 700;
    //color: #313133;
    background: #4FD1C5;
    background: linear-gradient(90deg, rgba(129, 230, 217, 1) 0%, rgba(79, 209, 197, 1) 100%);
    border: none;
    border-radius: 1000px;
    box-shadow: 3px 3px 24px rgba(79, 209, 197, .64);
    transition: all 0.3s ease-in-out 0s;
    cursor: pointer;
    outline: none;
    position: relative;
    padding: 1.25rem;
    transform: translateY(-6px);
  }
}

</style>

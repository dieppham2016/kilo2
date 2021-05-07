<template>
  <el-row class="queue--header">
    <el-row type="flex" justify="center">
      <b class="queue--header__item">Tên trung tâm</b>
    </el-row>
  </el-row>
</template>
<script>
export default {
  name: 'QueueHeader',
  props: {
    headerHeight: {
      type: Number,
      default: 0,
    },
  },
  computed: {
    getHeaderHeight() {
      return this.headerHeight * (1 / 10);
    },
    getHeaderItemHeight() {
      return this.getHeaderHeight / 2 + 'px';
    },
    rightColumnWidth() {
      return 3 / 16 * 100 + '%';
    },
  },
};
</script>

<style lang="scss" scoped>
.queue--header {
  .queue--header__item {
    font-size: 2rem;
    padding: .25rem .75rem;
    line-height: 3;
  }

  .queue--header__right-column {
    position: absolute;
    right: 0;
    top: 0;
    text-align: center;

    .queue--header__right-column__item {
      padding: .25rem .75rem;
      line-height: 2;
      font-size: 2rem;
      font-weight: bold;
    }
  }
}
</style>

<template>
  <el-row>
    <div
      class="screen--background"
      :style="{'background-image': 'url(' + require('../../assets/menu-lcd-frame-02.png') +')'}"
    />
    <div ref="grid" class="screen--wrapper">
<!--      <div class="screen&#45;&#45;slide" :style="{height: slideHeight + 'px', top: slideOffsetTop + 'px'}"><main-slide :slide-height="slideHeight" :collections="queueConfig.collections" /></div>-->
<!--      <div class="screen&#45;&#45;queue"><queue-view-2 :view-height="screenHeight" /></div>-->
      <div class="screen--slide" :style="{ top: slideOffsetTop + 'px' }">
        <main-slide :slide-height="slideHeight" :collections="queueConfig.collections" />
      </div>
      <div class="screen--queue">
        <queue-view-2 :view-height="screenHeight" :view-width="viewWidth" />
      </div>
    </div>
  </el-row>
</template>

<script>
import MainSlide from '@/views/queue/components/MainSlide';
import QueueView2 from '@/views/queue/components/QueueView2';
import Resource from '@/api/resource';

const QueueContentResource = new Resource('hang-cho');

export default {
  name: 'Queue',
  components: { QueueView2, MainSlide },
  data() {
    return {
      screenHeight: 0,
      screenWidth: 0,
      queueConfig: [],
    };
  },
  computed: {
    viewWidth() {
      return this.screenWidth * (3.8 / 16);
    },
    slideHeight() {
      return (this.screenWidth / 16 * 12.1) / 16 * 9 + (this.screenHeight * 0.0001);
    },
    slideOffsetTop() {
      return this.screenHeight - (this.screenWidth / 16 * 12.1) / 16 * 9;
    },
  },
  created() {
    this.getConfig();
  },
  mounted() {
    this.$nextTick(() => {
      this.onResize();
      window.addEventListener('resize', this.onResize);
    });
  },
  methods: {
    async getConfig() {
      const { data } = await QueueContentResource.index();
      this.queueConfig = data;
    },
    onResize() {
      // this.screenHeight = window.innerHeight - this.$refs.grid.getBoundingClientRect().top;
      this.screenWidth = this.$refs.grid.clientWidth;
      this.screenHeight = this.screenWidth / 16 * 9;
    },
  },
};
</script>

<style lang="scss" scoped>
.screen--background {
  z-index: 9999;
  width: 100vw;
  height: 100vh;
  position: absolute;
  top: 0;
  left: 0;
  background-repeat: no-repeat;
  background-size: contain;
  //background: transparent;
  //background-image: url('/assets/menu-lcd-frame-02.png');
  //background: rgb(0, 0, 0);
  //background: linear-gradient(90deg, rgb(0, 0, 0) 0%, rgb(0, 0, 0) 50%, rgb(0, 0, 0) 100%);
  overflow-y: hidden;
}

.screen--wrapper {
  display: block;
  position: relative;

  .screen--slide {
    z-index: -1;
    position: absolute;
    left: 0;
    width: percentage(12.1/16);
    //border-top: 2px solid #ffffff;
    //box-shadow: 1px 1px 5px #cccccc;
    background: transparent;
  }

  .screen--queue {
    z-index: 10000;
    position: absolute;
    right: 0;
    top: 0;
    width: percentage(3.8/16);
    overflow-y: hidden;
  }
}
</style>

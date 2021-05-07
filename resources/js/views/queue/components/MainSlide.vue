<template>
  <el-carousel
    :interval="5000"
    arrow="never"
    indicator-position="none"
    :height="carouselHeight"
  >
    <el-carousel-item v-for="item in collectionList" :key="item.id">
      <img :ref="item.id" :src="item.url" :alt="item.name" :style="{ width: '100%', height: carouselHeight}">
    </el-carousel-item>
  </el-carousel>
</template>
<script>

export default {
  name: 'MainSlide',
  props: {
    collections: {
      required: true,
      type: Array,
      default: () => [],
    },
    slideHeight: {
      type: Number,
      default: 0,
    },
  },
  data() {
    return {
      collectionList: [],
    };
  },
  computed: {
    carouselHeight() {
      return this.slideHeight + 'px';
    },
  },
  watch: {
    collections(array) {
      const collections = array.map(x => {
        return x.item;
      });
      this.collectionList = collections.reduce((a, b) => a.concat(b), []);
    },
    carouselHeight(val) {
    },
  },
  methods: {
  },
};
</script>

<style scoped>

</style>

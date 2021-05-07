<template>
  <el-row style="border-bottom: 1px solid #00aaff; margin-bottom: 15px">
    <el-row style="margin-bottom: 10px">
      <span class="text-bold-16" style="color: #1475be">{{ title }}</span>
    </el-row>

    <el-row style="margin: 15px">
      <slot />
    </el-row>

  </el-row>
</template>

<script>
export default {
  name: 'FormSection',
  props: {
    title: {
      type: String,
      default: '',
    },
  },
};
</script>

<style scoped>

</style>

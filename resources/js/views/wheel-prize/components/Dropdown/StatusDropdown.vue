<template>
  <el-dropdown :hide-on-click="false" :show-timeout="100" trigger="click">
    <el-button plain>
      {{ $t('status.title') }} ({{ status.length }})
      <i class="el-icon-caret-bottom el-icon--right" />
    </el-button>
    <el-dropdown-menu slot="dropdown" class="no-border">
      <el-checkbox-group v-model="status" style="padding: 5px 15px;">
        <el-checkbox
          v-for="item in options"
          :key="item.value"
          :label="item.id"
        >
          {{ item.text }}
        </el-checkbox>
      </el-checkbox-group>
    </el-dropdown-menu>
  </el-dropdown>
</template>

<script>
export default {
  props: {
    value: {
      required: true,
      default: () => [],
      type: Array,
    },
    options: {
      default: null,
      type: Array,
    },
  },
  data() {
    return {
    };
  },
  computed: {
    status: {
      get() {
        return this.value;
      },
      set(val) {
        this.$emit('input', val);
      },
    },
  },
};
</script>

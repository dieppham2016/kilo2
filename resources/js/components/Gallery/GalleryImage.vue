<template>
  <transition-group
    tag="ul"
    :class="[
      'el-upload-list',
      'el-upload-list--' + listType,
      { 'is-disabled': disabled }
    ]"
    name="el-list"
  >
    <li
      v-for="file in files"
      :key="file.id"
      :class="['el-upload-list__item', 'is-' + file.status, focusing ? 'focusing' : '']"
      tabindex="0"
      @keydown.delete="!disabled && $emit('remove', file)"
      @focus="focusing = true"
      @blur="focusing = false"
      @click="focusing = false"
    >
      <slot :file="file">
        <img
          v-if="['picture-card', 'picture'].indexOf(listType) > -1"
          class="el-upload-list__item-thumbnail"
          :src="file.url"
          alt=""
        >
        <a class="el-upload-list__item-name" @click="handleClick(file)">
          <i class="el-icon-document" />{{ file.name }}
        </a>
        <label class="el-upload-list__item-status-label">
          <i
            :class="{
              'el-icon-upload-success': true,
              'el-icon-circle-check': listType === 'text',
              'el-icon-check': ['picture-card', 'picture'].indexOf(listType) > -1
            }"
          />
        </label>
        <i v-if="!disabled" class="el-icon-close" @click="handleRemove(file, originalCollection)" />
        <i v-if="!disabled" class="el-icon-close-tip">{{ t('confirm.delete') }}</i> <!--因为close按钮只在li:focus的时候 display, li blur后就不存在了，所以键盘导航时永远无法 focus到 close按钮上-->

        <span v-if="listType === 'picture-card'" class="el-upload-list__item-actions">
          <span
            v-if="handlePreview && listType === 'picture-card'"
            class="el-upload-list__item-preview"
            @click="handlePreview(file, files)"
          >
            <i class="el-icon-zoom-in" />
          </span>
          <span
            v-if="!disabled"
            class="el-upload-list__item-delete"
            @click="handleRemove(file, originalCollection)"
          >
            <i class="el-icon-delete" />
          </span>
        </span>
      </slot>
    </li>
    <li
      key="upload"
      :class="['el-upload-list__item', 'is-', focusing ? 'focusing' : '']"
    >
      <el-upload
        ref="upload"
        multiple
        :data="{expected_file: 'image', collection_name: originalCollection}"
        :file-list="fileListUpload"
        list-type="picture-card"
        action="/api/media"
        auto-upload
        :on-success="handleSuccess"
        :on-exceed="handleExceed"
        :limit="limit"
      >
        <i class="el-icon-plus" />
      </el-upload>
    </li>
  </transition-group>
</template>
<script>
import Locale from 'element-ui/src/mixins/locale';

export default {

  name: 'GalleryImage',
  components: {},

  mixins: [Locale],

  props: {
    files: {
      type: Array,
      default() {
        return [];
      },
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    handlePreview: {
      type: Function,
      default: () => {},
    },
    listType: {
      type: String,
      default: 'picture-card',
    },
    originalCollection: {
      require: true,
      type: String,
      default: '',
    },
    onUploadSuccess: {
      type: Function,
      default: () => {},
    },
    onRemove: {
      type: Function,
      default: () => {},
    },
  },

  data() {
    return {
      focusing: false,
      form: {},
      fileListUpload: [],
      limit: 6,
    };
  },
  methods: {
    handleExceed() {
      this.$notify({
        title: this.$t('status.failure'),
        message: this.$t('validate.limitImage') + this.limit,
        type: 'error',
        duration: 2000,
      });
    },
    handleRemove(file, collection) {
      this.onRemove(file, collection);
    },
    handleSuccess(response, file, fileList) {
      this.fileListUpload = [];
      this.onUploadSuccess();
    },
    parsePercentage(val) {
      return parseInt(val, 10);
    },
    handleClick(file, fileList) {
      this.handlePreview && this.handlePreview(file, fileList);
    },
  },
};
</script>

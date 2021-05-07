<template>
  <el-row>
    <el-upload
      ref="upload"
      :multiple="multiple"
      :drag="drag"
      :data="data"
      :accept="acceptFileType.toString()"
      action="/api/media"
      :auto-upload="autoUpload"
      :show-file-list="showFileList"
      list-type="picture-card"
      :on-preview="handlePictureCardPreview"
      :on-change="handleChange"
      :on-success="handleSuccess"
      :on-exceed="handleExceed"
      :limit="limit"
    >
      <i class="el-icon-plus" />
    </el-upload>
    <el-dialog :visible.sync="dialogVisible">
      <img width="100%" :src="dialogImageUrl" alt="">
    </el-dialog>
  </el-row>
</template>

<script>

export default {
  props: {
    action: {
      type: String,
      default: '',
      require: true,
    },
    limit: {
      type: Number,
      default: 5,
    },
    drag: Boolean,
    showFileList: Boolean,
    autoUpload: Boolean,
    multiple: Boolean,
    onSuccess: {
      type: Function,
      default: () => {},
    },
    onChange: {
      type: Function,
      default: () => {},
    },
    data: {
      type: Object,
      default: () => {},
    },
  },
  data() {
    return {
      dialogImageUrl: '',
      dialogVisible: false,
      list: {},
      acceptFileType: ['image/jpeg', 'image/jpeg'],
    };
  },
  methods: {
    handleSuccess(response, file, fileList) {
      this.onSuccess(response, file, fileList);
    },
    handleChange(file, fileList) {
      if (this.isValidImage(file)) {
        this.$emit('input', fileList);
      } else {
        const fileIndex = fileList.map(e => {
          return e.uid;
        }).indexOf(file.uid);
        fileList.splice(fileIndex, 1);
        this.$notify({
          title: this.$t('status.failure'),
          message: this.$t('validate.invalidFileType'),
          type: 'error',
          duration: 2000,
        });
      }
    },
    handlePictureCardPreview(file) {
      this.dialogImageUrl = file.url;
      this.dialogVisible = true;
    },
    handleExceed() {
      this.$notify({
        title: this.$t('status.failure'),
        message: this.$t('validate.limitImage') + this.limit,
        type: 'error',
        duration: 2000,
      });
    },
    isValidImage(file) {
      return this.acceptFileType.includes(file.raw.type);
    },
  },
};
</script>

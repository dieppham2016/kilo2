<template>
  <el-dialog :width="dialogWidth" center :title="imageId ? $t('actions.edit') : $t('actions.create') " visible :before-close="handleCancel">
    <el-form ref="form" :model="form" :rules="rules">
      <el-form-item style="margin-bottom: 40px;" prop="collection_name">
        <MdInput v-model="form.collection_name" :minlength="4" :maxlength="40" name="collection_name" required>
          {{ $t('form.media.collection_name') }}
        </MdInput>
      </el-form-item>

      <el-form-item>
        <el-radio-group v-model="form.expected_file" size="mini">
          <el-radio-button
            v-for="item in typeList"
            :key="item"
            :label="item"
            :value="item"
          >
            {{ $t('form.media.' + item) }}
          </el-radio-button>
        </el-radio-group>
      </el-form-item>

      <el-divider content-position="left"><h3 style="color: #1890ff">{{ $t('form.media.' + form.expected_file) }}</h3></el-divider>

      <el-form-item prop="filesImage">
        <MultipleImage
          ref="upload"
          v-model="form.filesImage"
          :data="form"
          :limit="20"
          multiple
          show-file-list
          :on-success="handleSuccess"
        />
      </el-form-item>
    </el-form>
    <span slot="footer" class="dialog-footer">
      <el-button @click="handleCancel">{{ $t('actions.cancel') }}</el-button>
      <el-button v-loading="loading" type="primary" @click="handleConfirm">{{ $t('actions.confirm') }}</el-button>
    </span>
  </el-dialog>
</template>

<script>
import { validNameWithNumber } from '@/utils/validate';
import MdInput from '@/components/MDinput/index';
import MultipleImage from '@/components/Upload/MultipleImage';

const defaultForm = {
  collection_name: '',
  filesImage: {},
  expected_file: 'image',
};
export default {
  name: 'DetailForm',
  components: { MultipleImage, MdInput },
  props: {
    imageId: {
      type: Number,
      default: null,
    },
    dialogWidth: {
      type: String,
      default: '',
    },
  },
  data() {
    const validateRequire = (rule, value, callback) => {
      if (value === '') {
        callback(new Error(this.$t('form.media.' + rule.field) + this.$t('validate.required')));
      } else if (!validNameWithNumber(value.toString().toLowerCase())) {
        callback(new Error(this.$t('form.media.' + rule.field) + this.$t('validate.name')));
      } else {
        callback();
      }
    };
    const validateImageRequire = (rule, value, callback) => {
      if (!value.length) {
        callback(new Error(this.$t('form.media.' + rule.field) + this.$t('validate.required')));
      } if (value.length === 0) {
        callback(new Error(this.$t('form.media.' + rule.field) + this.$t('validate.numImage')));
      } else {
        callback();
      }
    };
    return {
      rules: {
        collection_name: [{ validator: validateRequire }],
        filesImage: [{ validator: validateImageRequire }],
      },
      form: Object.assign({}, defaultForm),
      loading: false,
      typeList: ['image'],
    };
  },
  methods: {
    async getImages() {
      //
    },
    handleCreate() {
      delete this.form.filesImage;
      console.log('Detail form: ', this.form);
      this.$refs.upload.$refs.upload.submit();
    },
    handleChange(file, fileList) {},
    handleSuccess() {
      this.onClose();
    },
    handleUpdate(id) {},
    onClose() {
      this.$emit('onClose', false);
    },
    handleCancel() {
      this.onClose();
    },
    handleConfirm() {
      this.$refs.form.validate(valid => {
        if (valid) {
          if (this.imageId) {
            this.handleUpdate(this.imageId);
          } else {
            this.handleCreate();
          }
        } else {
          return false;
        }
      });
    },
  },
};
</script>

<style scoped>

</style>

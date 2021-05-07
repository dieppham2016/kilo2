<template>
  <el-row :gutter="20">

    <sticky :class-name="'sub-navbar draft'">
      <el-row justify="left" type="flex">
        <div style="margin-left: auto">
          <el-button
            style="margin-left: 10px;"
            type="primary"
            @click="handleSave"
          >
            {{ $t('actions.save') }}
          </el-button>
        </div>

      </el-row>
    </sticky>

    <el-col :md="{span: 8, offset: 1}">
      <el-form ref="form" :model="form" :rules="rules">

        <el-form-item style="margin-bottom: 40px;" prop="header_vi">
          <el-divider content-position="left"><span style="color: #1FA0BA">{{ $t('table.header') }}</span></el-divider>
          <MdInput v-model="form.header_vi" :minlength="4" :maxlength="60" name="header_vi" required>
            {{ $t('form.queue_content.header_vi') }}
          </MdInput>
        </el-form-item>
        <el-form-item style="margin-bottom: 40px;" prop="header_en">
          <MdInput v-model="form.header_en" :minlength="4" :maxlength="60" name="header_en" required>
            {{ $t('form.queue_content.header_en') }}
          </MdInput>
        </el-form-item>

        <el-form-item>
          <el-divider content-position="left"><span style="color: #1FA0BA">{{ $t('form.media.select_mode') }}</span></el-divider>
          <el-tooltip :content="$t('form.media.slide') + ': ' + $t('form.media.' + form.mode_active)" placement="top">
            <el-switch
              v-model="form.mode_active"
              active-color="#13ce66"
              inactive-color="#1FA0BA"
              active-value="video"
              inactive-value="image"
              @change="handleChangeMode"
            />
          </el-tooltip>
          <span style="margin-left: .75rem;">{{ $t('form.media.' + form.mode_active) }}</span>
        </el-form-item>

        <el-form-item prop="collections">
          <el-divider content-position="left"><span style="color: #1FA0BA">{{ $t('table.image_slide') }}</span></el-divider>
          <el-select v-model="form.collections" multiple :placeholder="$t('form.queue_content.select_collection')">
            <el-option
              v-for="item in collectionList"
              :key="item.id"
              :label="item.display_name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>

      </el-form>
    </el-col>
  </el-row>
</template>

<script>
import MdInput from '@/components/MDinput';
import Sticky from '@/components/Sticky/index';
import { validName } from '@/utils/validate';
import Resource from '@/api/resource';

const MediaResource = new Resource('media');
const QueueContentResource = new Resource('hang-cho');
const defaultForm = {
  header_vi: '',
  header_en: '',
  collections: [],
  mode_active: 'image',
};

export default {
  name: 'QueueManagerForm',
  components: { Sticky, MdInput },
  data() {
    const validateRequire = (rule, value, callback) => {
      if (value === '') {
        callback(new Error(this.$t('validate.' + rule.field) + this.$t('validate.required')));
      } else if (!validName(value.toString().toLowerCase())) {
        callback(new Error(this.$t('validate.' + rule.field) + this.$t('validate.name')));
      } else if (value.length < 4) {
        callback(new Error(this.$t('validate.length') + 4));
      } else {
        callback();
      }
    };
    const validateCollectionRequired = (rule, value, callback) => {
      if (value === '' || value.length < 1) {
        callback(new Error(this.$t('validate.' + rule.field) + this.$t('validate.required')));
      } else {
        callback();
      }
    };
    return {
      rules: {
        header_vi: [{ validator: validateRequire }],
        header_en: [{ validator: validateRequire }],
        collections: [{ validator: validateCollectionRequired }],
      },
      form: Object.assign({}, defaultForm),
      collectionList: [],
      getCollectionOption: {},
    };
  },
  created() {
    this.getCollectionOption = {
      type: this.form.mode_active,
    };
    this.getCollection(this.getCollectionOption);
    this.getQueueContent();
  },
  methods: {
    async getQueueContent() {
      const { data } = await QueueContentResource.index();
      if (data) {
        const ids = data.collections.map(x => {
          return x.id;
        });
        this.form = data;
        this.form.collections = ids;
      }
    },
    async getCollection(option) {
      const { data } = await MediaResource.index(option);
      this.collectionList = data;
    },
    handleChangeMode(val) {
      this.getCollection({ type: val });
    },
    handleSave() {
      this.$refs.form.validate(valid => {
        if (valid) {
          QueueContentResource.update(1, this.form)
            .then(response => {
              if (response.status === 200) {
                this.$notify({
                  title: this.$t('status.success'),
                  message: this.$t('notify.saved'),
                  type: 'success',
                  duration: 2000,
                });
                this.getCollection(this.getCollectionOption);
                this.getQueueContent();
              } else if (response.status === 204) {
                this.$notify({
                  title: this.$t('status.failure'),
                  message: this.$t('notify.notFound'),
                  type: 'success',
                  duration: 2000,
                });
              }
            })
            .catch(err => {
              this.$notify({
                title: this.$t('status.failure'),
                message: err,
                type: 'error',
                duration: 2000,
              });
            });
        } else {
          return false;
        }
      });
      console.log(this.form);
    },
  },
};
</script>

<style scoped>

</style>

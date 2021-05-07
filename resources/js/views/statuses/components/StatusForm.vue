<template>
  <el-dialog :width="dialogWidth" center :title="title" :visible.sync="visible" :before-close="onCancel" @open="handleOpen">
    <el-form ref="form" :model="form" :rules="rules">
      <el-form-item style="margin-bottom: 40px;" prop="name">
        <MDinput v-model="form.name" :minlength="4" :maxlength="16" name="name" required>
          {{ $t('status.name') }}
        </MDinput>
      </el-form-item>
      <el-form-item style="margin-bottom: 40px;" prop="description">
        <MDinput v-model="form.description" :maxlength="30" name="status" required>
          {{ $t('table.description') }}
        </MDinput>
      </el-form-item>

      <el-divider content-position="center"><span style="color: #1890ff">{{ $t('table.hierarchical_display') }}</span></el-divider>

      <el-form-item>
        <el-radio-group v-model="form.display_level" size="mini">
          <el-radio-button
            v-for="item in options"
            :key="item"
            :ref="item"
            :label="item"
            :value="item"
            :class="item"
          >
            {{ $t('level.' + item) }}
          </el-radio-button>
        </el-radio-group>
      </el-form-item>
    </el-form>
    <span slot="footer" class="dialog-footer">
      <el-button @click="onCancel">{{ $t('actions.cancel') }}</el-button>
      <el-button v-loading="loading" type="primary" @click="onConfirm">{{ $t('actions.confirm') }}</el-button>
    </span>
  </el-dialog>
</template>

<script>
import MDinput from '@/components/MDinput';
import Resource from '@/api/resource';
import { validName } from '@/utils/validate';

const StatusResource = new Resource('trang-thai');

const defaultForm = {
  name: '',
  description: '',
  display_level: 'waiting',
};

export default {
  name: 'StatusForm',
  components: {
    MDinput,
  },
  props: {
    visible: {
      type: Boolean,
      default: false,
    },
    title: {
      type: String,
      default: '',
    },
    dialogWidth: {
      type: String,
      default: '200px',
    },
    statusId: {
      type: Number,
      default: null,
    },
  },
  data() {
    const validateRequire = (rule, value, callback) => {
      if (value === '') {
        callback(new Error(this.$t('table.' + rule.field) + this.$t('validate.required')));
      } else if (!validName(value.toString().toLowerCase())) {
        callback(new Error(this.$t('table.' + rule.field) + this.$t('validate.name')));
      } else if (value.length < 4) {
        callback(new Error(this.$t('validate.length') + 4));
      } else {
        callback();
      }
    };
    return {
      rules: {
        name: [{ validator: validateRequire }],
        description: [{ validator: validateRequire }],
      },

      form: Object.assign({}, defaultForm),
      loading: false,
      status: null,
      options: ['default', 'waiting', 'warning', 'success', 'danger'],
    };
  },
  methods: {
    handleOpen() {
      this.getStatus();
    },
    onClose() {
      this.$emit('onClose', false);
    },
    onCancel() {
      this.onClose();
    },
    getStatus() {
      if (this.statusId) {
        this.listLoading = true;
        StatusResource.show(this.statusId)
          .then(response => {
            if (response.status === 200) {
              this.form = response.data;
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
        this.listLoading = false;
      }
    },
    onConfirm() {
      this.$refs.form.validate(valid => {
        if (valid) {
          if (this.statusId) {
            this.onUpdate(this.statusId);
          } else {
            this.onCreate();
          }
        } else {
          return false;
        }
      });
    },
    onCreate() {
      this.loading = true;

      this.status = this.form.name;
      StatusResource.store(this.form)
        .then(response => {
          console.log(response.data);
          if (response.status === 200) {
            this.$notify({
              title: this.$t('status.success'),
              message: this.status + this.$t('notify.result.added'),
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
      this.loading = false;
      this.form = Object.assign({}, defaultForm);
      this.onClose();
    },
    onUpdate(id) {
      this.loading = true;

      this.status = this.form.name;
      StatusResource.update(id, this.form)
        .then(response => {
          console.log(response.data);
          if (response.status === 200) {
            this.$notify({
              title: this.$t('status.success'),
              message: this.status + this.$t('notify.result.added'),
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
      this.loading = false;
      this.form = Object.assign({}, defaultForm);
      this.onClose();
    },
  },
};
</script>

<style scoped>

</style>

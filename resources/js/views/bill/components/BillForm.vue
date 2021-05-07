<template>
  <el-dialog :width="dialogWidth" center :title="title" :visible.sync="visible" :before-close="onCancel">
    <el-form ref="form" :model="form" :rules="rules">
      <el-form-item style="margin-bottom: 40px;" prop="bill_no">
        <MDinput v-model="form.bill_no" :minlength="4" :maxlength="4" name="bill" required>
          {{ $t('table.bill_no') }}
        </MDinput>
      </el-form-item>

      <el-divider content-position="left"><span style="color: #1890ff">{{ $t('table.status') }}</span></el-divider>

      <el-form-item>
        <el-radio-group v-model="form.status_id" size="mini">
          <el-radio-button
            v-for="item in statusList"
            :key="item.name"
            :label="item.id"
            :value="item.id"
            :class="item.display_level"
          >
            {{ $t('level.' + item.name) }}
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
const BillResource = new Resource('hoa-don');

const defaultForm = {
  status_id: '1',
  bill_no: '',
};

export default {
  name: 'BillForm',
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
    billId: {
      type: Number,
      default: null,
    },
    statusList: {
      require: true,
      type: Array,
      default: () => [],
    },
  },
  data() {
    const validateRequire = (rule, value, callback) => {
      if (value === '') {
        callback(new Error(this.$t('table.' + rule.field) + this.$t('validate.required')));
      } else if (isNaN(parseInt(value))) {
        callback(new Error(this.$t('table.' + rule.field) + this.$t('validate.number')));
      } else if (value.length < 4) {
        callback(new Error(this.$t('validate.length') + 4));
      } else {
        callback();
      }
    };
    return {
      rules: {
        bill_no: [{ validator: validateRequire }],
      },

      form: Object.assign({}, defaultForm),
      loading: false,
      bill_no: null,
      statuses: null,
    };
  },
  created() {
    if (this.billId) {
      BillResource.show(this.billId).then(response => {
        this.form = response.data;
      });
    }
  },
  methods: {
    onClose() {
      this.$emit('onClose', false);
      this.loading = false;
      this.form = Object.assign({}, defaultForm);
    },
    onCancel() {
      this.onClose();
    },
    onConfirm() {
      this.$refs.form.validate(valid => {
        if (valid) {
          if (this.billId) {
            this.onUpdate(this.billId);
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
      this.bill_no = this.form.bill_no;
      BillResource.store(this.form)
        .then(response => {
          if (response.data) {
            this.$notify({
              title: this.$t('status.success'),
              message: this.bill_no + this.$t('result.added'),
              type: 'success',
              duration: 2000,
            });
          }
          this.onClose();
        })
        .catch(err => {
          this.$notify({
            title: this.$t('status.failure'),
            message: err,
            type: 'error',
            duration: 2000,
          });
          this.onClose();
        });
    },
    onUpdate(id) {
      this.loading = true;
      this.bill_no = this.form.bill_no;
      BillResource.update(id, this.form)
        .then(response => {
          if (response.data) {
            this.$notify({
              title: this.$t('status.success'),
              message: this.bill_no + this.$t('notify.result.edited'),
              type: 'success',
              duration: 2000,
            });
          }
          this.onClose();
        })
        .catch(err => {
          this.$notify({
            title: this.$t('status.failure'),
            message: err,
            type: 'error',
            duration: 2000,
          });
          this.onClose();
        });
    },
  },
};
</script>

<style scoped>

</style>

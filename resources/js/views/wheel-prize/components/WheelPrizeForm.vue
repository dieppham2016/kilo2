<template>
  <el-dialog :width="dialogWidth" center :title="formName" :visible.sync="visible" :before-close="onCancel">
    <el-form ref="form" :model="form" :rules="rules" label-width="150px" label-position="left">

      <el-form-item :label="$t('wheel.prize.form.type')">
        <el-radio-group v-model="form.type" size="mini" @change="toggleDisplayPrizeInputName">
          <el-radio-button
            v-for="item in prizeType"
            :key="item.label"
            :label="item.label"
            :value="item.value"
          />
        </el-radio-group>
      </el-form-item>

      <el-form-item v-if="!prizeIsTicket" :label="$t('wheel.prize.form.name')">
        <el-input v-model="form.name" name="name" />
      </el-form-item>

      <el-form-item v-if="prizeIsTicket" :label="$t('wheel.prize.form.amount')">
        <el-input-number v-model="form.amount" name="amount" :min="1" />
      </el-form-item>

      <el-form-item :label="$t('wheel.prize.form.rate')">
        <el-input-number v-model="form.rate" :min="1" :max="90" />
      </el-form-item>

      <el-form-item v-if="!prizeIsTicket" :label="$t('wheel.prize.form.moreInfo')">
        <el-input
          v-model="form.more_info"
          type="textarea"
          placeholder="Please input"
          maxlength="100"
          show-word-limit
          name="more_info"
        />
      </el-form-item>

    </el-form>
    <span slot="footer" class="dialog-footer">
      <el-button v-loading="isLoading" type="primary" @click="onConfirm">{{ $t('wheel.prize.form.actions.confirm') }}</el-button>
      <el-button @click="onCancel">{{ $t('wheel.prize.form.actions.cancel') }}</el-button>
    </span>
  </el-dialog>
</template>

<script>

import Resource from '@/api/resource';
import { validHEXColor, validName } from '@/utils/validate';

const defaultForm = {
  name: '',
  type: 'Ticket',
  amount: 1,
  rate: 0,
  more_info: '',
};

const PrizeResource = new Resource('wheel/prizes');

export default {
  name: 'WheelPrizeForm',
  components: {},
  props: {
    prize: {
      type: Object,
      default: () => null,
    },
    dialogWidth: {
      type: String,
      default: '400px',
    },
    visible: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    const amountValidate = (rule, value, callback) => {
      if (value === '') {
        callback(new Error(this.$t('wheel.prize.form.' + rule.field) + this.$t('validate.required')));
      } else if (isNaN(parseInt(value))) {
        callback(new Error(this.$t('wheel.prize.form.' + rule.field) + this.$t('validate.number')));
      } else if (value.length < 1) {
        callback(new Error(this.$t('validate.length') + 1));
      } else {
        callback();
      }
    };
    const colorValidate = (rule, value, callback) => {
      if (validHEXColor(value)) {
        callback();
      } else {
        callback(new Error(this.$t('wheel.prize.form.' + rule.field) + this.$t('validate.isInvalid')));
      }
    };
    const validateName = (rule, value, callback) => {
      if (value) {
        if (validName(value)) {
          callback();
        } else {
          callback(new Error(this.$t('wheel.prize.form.' + rule.field) + this.$t('validate.isInvalid')));
        }
      } else {
        callback();
      }
    };
    return {
      form: Object.assign({}, defaultForm),
      isLoading: false,
      prizeIsTicket: true,
      formName: '',
      prizeType: [
        {
          label: 'Ticket',
          value: 'Ticket',
        },
        {
          label: 'Code',
          value: 'Code',
        },
      ],
      collections: [],
      rules: {
        name: [{ validate: validateName }],
        amount: [{ validate: amountValidate }],
        background: [{ validate: colorValidate }],
        text_color: [{ validate: colorValidate }],
        more_info: [{ validate: validateName }],
      },

    };
  },
  watch: {
  },
  created() {
    this.formName = this.prize ? this.$t('form.title.edit') : this.$t('form.title.create');
    if (this.prize != null) {
      this.form = Object.assign({}, this.prize);
      this.prizeIsTicket = this.form.type === 'Ticket';
    }
  },
  methods: {
    onClose() {
      this.$emit('onClose', false);
      this.isLoading = false;
      this.form = Object.assign({}, defaultForm);
    },
    onCancel() {
      this.onClose();
    },
    onConfirm() {
      this.$refs.form.validate(valid => {
        if (valid) {
          if (this.prize) {
            this.handleUpdatePrize(this.prize.id);
          } else {
            this.handleCreatePrize();
          }
        } else {
          return false;
        }
      });
    },
    handleCreatePrize() {
      this.isLoading = true;
      PrizeResource.store(this.form)
        .then(response => {
          if (response.status === 200) {
            this.$notify({
              title: this.$t('status.success'),
              message: response.data.message + this.$t('notify.result.added'),
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
    handleUpdatePrize(id) {
      this.isLoading = true;
      PrizeResource.update(id, this.form)
        .then(response => {
          if (response.data) {
            this.$notify({
              title: this.$t('status.success'),
              message: response.data.message + this.$t('notify.result.updated'),
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
    toggleDisplayPrizeInputName(val) {
      this.prizeIsTicket = val === 'Ticket';
    },
  },
};
</script>

<style scoped>

</style>

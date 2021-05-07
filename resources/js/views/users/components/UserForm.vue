<template>
  <el-dialog :title="'Create new user'" center :visible.sync="visible" :width="width" :before-close="handleClose" @opened="handleOpen">
    <div v-loading="userCreating" class="form-container">
      <el-form ref="userForm" :model="newUser" :rules="rules" label-position="left" label-width="250px" style="max-width: 600px;">
        <el-form-item :label="$t('form.user.role')" prop="role">
          <el-select v-model="newUser.role" class="filter-item" :placeholder="$t('form.user.selectRole')">
            <el-option v-for="item in nonAdminRoles" :key="item" :label="item | uppercaseFirst" :value="item" />
          </el-select>
        </el-form-item>
        <el-form-item :label="$t('form.user.name')" prop="name">
          <el-input v-model="newUser.name" :maxlength="40" show-word-limit />
        </el-form-item>
        <el-form-item :label="$t('form.user.username')" prop="username">
          <el-input v-model="newUser.username" :maxlength="20" show-word-limit />
        </el-form-item>
        <el-form-item :label="$t('form.user.staff_code') + ' (' + $t('validate.optional') + ')'" prop="staff_code">
          <el-input v-model="newUser.staff_code" :maxlength="6" show-word-limit />
        </el-form-item>
        <el-form-item :label="$t('form.user.uid') + ' (' + $t('validate.optional') + ')'" prop="uid">
          <el-input v-model="newUser.uid" :maxlength="10" show-word-limit />
        </el-form-item>
        <el-form-item :label="$t('form.user.email') + ' (' + $t('validate.optional') + ')'" prop="email">
          <el-input v-model="newUser.email" />
        </el-form-item>
        <el-form-item :label="$t('form.user.phone') + ' (' + $t('validate.optional') + ')'" prop="phone">
          <el-input v-model="newUser.phone" :maxlength="11" show-word-limit />
        </el-form-item>
        <el-form-item :label="$t('form.user.password')" prop="password">
          <el-input v-model="newUser.password" show-password />
        </el-form-item>
        <el-form-item :label="$t('form.user.confirmPassword')" prop="confirmPassword">
          <el-input v-model="newUser.confirmPassword" show-password />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="handleCancel">
          {{ $t('actions.cancel') }}
        </el-button>
        <el-button type="primary" @click="handleConfirm()">
          {{ $t('actions.confirm') }}
        </el-button>
      </div>
    </div>
  </el-dialog>
</template>

<script>
import UserResource from '@/api/user';
const userResource = new UserResource();

import { validName, validUsername, validUid, validStaffCode, validEmail, validPhone } from '@/utils/validate';

export default {
  name: 'UserForm',
  props: {
    visible: {
      type: Boolean,
      default: false,
    },
    width: {
      type: String,
      default: '500px',
    },
    user: {
      type: Object,
      default: () => {},
    },
  },
  data() {
    const validateConfirmPassword = (rule, value, callback) => {
      if (value !== this.newUser.password) {
        callback(new Error('Password is mismatched!'));
      } else {
        callback();
      }
    };
    const validateRole = (rule, value, callback) => {
      if (value.length > 0) {
        callback();
      } else {
        callback(this.$t('form.user.' + rule.field) + this.$t('validate.required'));
      }
    };
    const validateName = (rule, value, callback) => {
      if (value) {
        if (!validName(value)) {
          callback(this.$t('form.user.' + rule.field) + this.$t('validate.isInvalid'));
        } else {
          callback();
        }
      } else {
        callback();
      }
    };
    const validateUsername = (rule, value, callback) => {
      if (value) {
        if (!validUsername(value)) {
          callback(this.$t('form.user.' + rule.field) + this.$t('validate.isInvalid'));
        } else {
          callback();
        }
      } else {
        callback();
      }
    };
    const validateEmail = (rule, value, callback) => {
      if (value) {
        if (!validEmail(value)) {
          callback(this.$t('form.user.' + rule.field) + this.$t('validate.isInvalid'));
        } else {
          callback();
        }
      } else {
        callback();
      }
    };
    const validateUID = (rule, value, callback) => {
      if (value) {
        if (!validUid(value)) {
          callback(this.$t('form.user.' + rule.field) + this.$t('validate.isInvalid'));
        } else {
          callback();
        }
      } else {
        callback();
      }
    };
    const validateStaffCode = (rule, value, callback) => {
      if (value) {
        value = value.toUpperCase();
        this.newUser.staff_code = value;
        if (!validStaffCode(value)) {
          callback(this.$t('form.user.' + rule.field) + this.$t('validate.isInvalid'));
        } else {
          callback();
        }
      } else {
        callback();
      }
    };
    const validatePhone = (rule, value, callback) => {
      if (value) {
        if (!validPhone(value)) {
          callback(this.$t('form.' + rule.field) + this.$t('validate.isInvalid'));
        } else {
          callback();
        }
      } else {
        callback();
      }
    };
    const validatePassword = (rule, value, callback) => {
      if (value === '') {
        callback(this.$t('form.user.' + rule.field) + this.$t('validate.required'));
      } else {
        callback();
      }
    };
    return {
      rules: {
        role: [{ validator: validateRole, trigger: 'change' }],
        name: [{ validator: validateName, trigger: 'blur' }],
        username: [{ validator: validateUsername, trigger: 'blur' }],
        uid: [{ validator: validateUID, trigger: 'blur' }],
        staff_code: [{ validator: validateStaffCode, trigger: 'blur' }],
        phone: [{ validator: validatePhone, trigger: 'blur' }],
        email: [{ validator: validateEmail, trigger: 'blur' }],
        password: [{ validator: validatePassword, trigger: 'blur' }],
        confirmPassword: [{ validator: validateConfirmPassword, trigger: 'blur' }],
      },
      newUser: {},
      userCreating: false,
      roles: ['admin', 'manager', 'staff'],
      nonAdminRoles: ['staff'],
    };
  },
  created() {
    this.resetNewUser();
  },
  methods: {
    handleClose() {
      this.$emit('onClose', false);
    },
    handleCancel() {
      this.handleClose();
    },
    handleOpen() {
      if (this.user) {
        this.newUser = this.user;
        this.newUser.role = this.user.roles[0];
        delete this.newUser.roles;
      } else {
        this.resetNewUser();
        this.$nextTick(() => {
          this.$refs['userForm'].clearValidate();
        });
      }
    },
    handleConfirm() {
      if (this.user) {
        this.handleUpdate();
      } else {
        this.handleCreate();
      }
    },
    handleUpdate() {
      this.$refs['userForm'].validate((valid) => {
        if (valid) {
          this.newUser.roles = [this.newUser.role];
          this.userCreating = true;
          userResource
            .update(this.newUser.id, this.newUser)
            .then(response => {
              this.$message({
                message: response.data.username + this.$t('notify.result.updated'),
                type: 'success',
                duration: 5 * 1000,
              });
              this.handleClose();
              this.resetNewUser();
              this.dialogFormVisible = false;
              this.handleFilter();
            })
            .catch(error => {
              console.log(error);
            })
            .finally(() => {
              this.userCreating = false;
            });
        } else {
          return false;
        }
      });
    },
    handleCreate() {
      this.$refs['userForm'].validate((valid) => {
        if (valid) {
          this.newUser.roles = [this.newUser.role];
          this.userCreating = true;
          userResource
            .store(this.newUser)
            .then(response => {
              this.$message({
                message: response.data.username + this.$t('notify.result.created'),
                type: 'success',
                duration: 5 * 1000,
              });
              this.handleClose();
              this.resetNewUser();
              this.dialogFormVisible = false;
              this.handleFilter();
            })
            .catch(error => {
              console.log(error);
            })
            .finally(() => {
              this.userCreating = false;
            });
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    resetNewUser() {
      this.newUser = {
        name: '',
        username: '',
        staff_code: '',
        uid: '',
        email: '',
        phone: '',
        password: '',
        confirmPassword: '',
        role: 'staff',
      };
    },
  },
};
</script>

<style lang='scss' scoped>
  .edit-input {
    padding-right: 100px;
  }
  .cancel-btn {
    position: absolute;
    right: 15px;
    top: 10px;
  }
  .dialog-footer {
    text-align: left;
    padding-top: 0;
    margin-left: 150px;
  }
</style>

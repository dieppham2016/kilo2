<template>
  <div class="app-container">
    <sticky :class-name="'sub-navbar draft'">
      <el-row justify="left" type="flex">

        <div style="margin-left: auto">
          <el-button
            style="margin-left: 10px;"
            type="info"
            icon="el-icon-refresh"
            @click="getData"
          >
            {{ $t('sticky.button.refresh') }}
          </el-button>
          <el-button
            style="margin-left: 10px;"
            type="primary"
            icon="el-icon-check"
            @click="handleUpdate"
          >
            {{ $t('sticky.button.save') }}
          </el-button>

        </div>

      </el-row>
    </sticky>
    <el-row style="margin: 15px" type="flex" justify="center">
      <el-col>
        <el-form ref="form" :model="form" :rules="rules" label-position="top">

          <form-section title="I/O connection">
            <el-form-item>
              <template v-slot:label>
                <div>
                  <span>{{ $t('wheel.config.form.io_ticket_pwm') }}: </span>
                  <span style="text-decoration: underline; color: #11987b">Milliseconds</span>
                </div>
              </template>

              <el-input-number v-model="form.io_ticket_pwm" :min="10" :step="10" />
              <b style="color: #00aaff; margin-left: 15px"> => {{ mills2second(form.io_ticket_pwm, 2) }} (s)</b>
            </el-form-item>

            <el-form-item>
              <template v-slot:label>
                <div>
                  <span>{{ $t('wheel.config.form.io_ticket_input') }}: </span>
                  <span style="text-decoration: underline; color: #11987b">LOW | HIGH</span>
                </div>
              </template>
              <el-radio-group v-model="form.io_ticket_input">
                <el-radio style="color: #0e74a7; font-weight: bold" :label="0">LOW</el-radio>
                <el-radio style="color: #aa0f35; font-weight: bold" :label="1">HIGH</el-radio>
              </el-radio-group>
            </el-form-item>

            <el-form-item>
              <template v-slot:label>
                <div>
                  <span>{{ $t('wheel.config.form.io_ticket_output') }}: </span>
                  <span style="text-decoration: underline; color: #11987b">LOW | HIGH</span>
                </div>
              </template>
              <el-radio-group v-model="form.io_ticket_output">
                <el-radio style="color: #0e74a7; font-weight: bold" :label="0">LOW</el-radio>
                <el-radio style="color: #aa0f35; font-weight: bold" :label="1">HIGH</el-radio>
              </el-radio-group>
            </el-form-item>

          </form-section>

          <form-section title="Time">

            <el-form-item>
              <template v-slot:label>
                <div>
                  <span>{{ $t('wheel.config.form.time_auto_play') }}: </span>
                  <span style="text-decoration: underline; color: #11987b">Milliseconds</span>
                </div>
              </template>
              <el-input-number v-model="form.time_auto_play" :min="1000" :step="1000" />
              <b style="color: #00aaff; margin-left: 15px"> => {{ mills2second(form.time_auto_play, 2) }} (s)</b>
            </el-form-item>

            <el-form-item>
              <template v-slot:label>
                <div>
                  <span>{{ $t('wheel.config.form.time_back_demo') }}: </span>
                  <span style="text-decoration: underline; color: #11987b">Milliseconds</span>
                </div>
              </template>
              <el-input-number v-model="form.time_back_demo" :min="1000" :step="1000" />
              <b style="color: #00aaff; margin-left: 15px"> => {{ mills2second(form.time_back_demo, 2) }} (s)</b>
            </el-form-item>

            <el-form-item>
              <template v-slot:label>
                <div>
                  <span>{{ $t('wheel.config.form.time_show_congratulation_short') }}: </span>
                  <span style="text-decoration: underline; color: #11987b">Milliseconds</span>
                </div>
              </template>
              <el-input-number v-model="form.time_show_congratulation_short" :min="1000" :step="1000" />
              <b style="color: #00aaff; margin-left: 15px"> => {{ mills2second(form.time_show_congratulation_short, 2) }} (s)</b>
            </el-form-item>

            <el-form-item>
              <template v-slot:label>
                <div>
                  <span>{{ $t('wheel.config.form.time_show_congratulation_long') }}: </span>
                  <span style="text-decoration: underline; color: #11987b">Milliseconds</span>
                </div>
              </template>
              <el-input-number v-model="form.time_show_congratulation_long" :min="1000" :step="1000" />
              <b style="color: #00aaff; margin-left: 15px"> => {{ mills2second(form.time_show_congratulation_long, 2) }} (s)</b>
            </el-form-item>

          </form-section>

        </el-form>
      </el-col>
    </el-row>
  </div>
</template>

<script>

import Sticky from '@/components/Sticky'; // Sticky header
import Resource from '@/api/resource';
import FormSection from '@/views/wheel-config/components/FormSection';
const WheelConfigResource = new Resource('wheel/config');

const defaultForm = {
  io_ticket_pwm: 100,
  io_ticket_input: 0,
  io_ticket_output: 0,
  time_back_demo: 5000,
  time_auto_play: 10000,
  time_show_congratulation_short: 5000,
  time_show_congratulation_long: 15000,
};

export default {
  name: 'WheelConfig',
  components: { FormSection, Sticky },
  data() {
    return {
      total: 0,
      data: [],
      query: {
        page: 1,
        limit: 10,
      },
      rules: {},
      form: Object.assign({}, defaultForm),
      isTableLoading: false,
      isDownloadLoading: false,
    };
  },
  created() {
    this.getData();
  },
  methods: {
    async getData() {
      this.isTableLoading = true;
      const { data } = await WheelConfigResource.index(this.query);
      this.data = data;
      if (data[0] !== 'undefined' || data[0] !== null || data.length > 0) {
        this.form = Object.assign({}, data[0]);
      } else {
        this.form = Object.assign({}, defaultForm);
      }
      this.isTableLoading = false;
    },
    handleUpdate() {
      this.$refs.form.validate((valid) => {
        if (valid) {
          console.log(this.form);
          WheelConfigResource.update(1, this.form)
            .then(response => {
              if (response.status === 201) {
                this.$notify({
                  title: this.$t('status.success'),
                  message: response.data.message + this.$t('notify.result.updated'),
                  type: 'success',
                  duration: 2000,
                });
              }
            })
            .catch(error => {
              this.$notify({
                title: this.$t('status.failure'),
                message: error,
                type: 'error',
                duration: 2000,
              });
            });
        }
      });
    },
    mills2second(mills, dec = 1) {
      return window._.floor(mills / 1000, dec);
    },
  },
};
</script>

<style scoped>

</style>

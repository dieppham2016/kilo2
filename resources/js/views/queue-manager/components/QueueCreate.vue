<template>
  <el-row type="flex" justify="center">
    <numpad :on-confirm="handleConfirm" :on-error="handleError" />
  </el-row>
</template>

<script>
import Numpad from '@/components/Numpad/Numpad';
import Resource from '@/api/resource';
import { getStatusId } from '@/utils';
const BillResource = new Resource('hoa-don');
const StatusResource = new Resource('trang-thai');
export default {
  name: 'QueueCreate',
  components: { Numpad },
  props: {},
  data() {
    return {
      input: '',
      statusList: {},
    };
  },
  created() {
    this.getStatusList();
  },
  methods: {
    async getStatusList() {
      const { data } = await StatusResource.index();
      this.statusList = data;
    },
    handleConfirm(val) {
      BillResource.store({ bill_no: val, status_id: getStatusId(this.statusList, 'waiting'), status: 'waiting' })
        .then(response => {
          if (response.status === 200) {
            this.$notify({
              type: 'success',
              title: this.$t('status.success'),
              message: this.$t('table.bill_no') + ' ' + val + this.$t('notify.result.added'),
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
    },
    handleError(length, minLength) {
      this.$notify({
        type: 'error',
        title: this.$t('status.failure'),
        message: this.$t('validate.charLength') + minLength,
        duration: 2000,
      });
    },
  },
};
</script>

<style lang="scss" scoped>
  .numpad--wrapper {
    width: 30rem;
    margin-top: 2rem;

    .numpad--input__wrapper {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      .numpad--input__display{
        display: flex;
        flex-grow: 1;
        justify-content: flex-end;
        padding: 1rem 3rem;
        font-size: 2rem;
        border: 1px solid #dddddd;
        user-select: none;
      }
    }

    .numpad--button__wrapper {
      width: 100%;
      height: 100%;

      .numpad--row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;

        .numpad--button {
          display: flex;
          padding: 1rem 3rem;
          font-size: 3rem;
          flex-grow: 1;
          justify-content: center;
          align-self: center;
          min-height: 5rem;
          height: 7rem;
          max-height: 10rem;
          width: 5rem;
          user-select: none;
        }
      }

    }
  }

</style>

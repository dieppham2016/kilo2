<template>
  <el-row type="flex" justify="center">
    <el-table
      :data="billList.filter(data => !search | data.bill_no.includes(search))"
      style="max-width: 60rem; margin-top: 2rem;"
      height="640"
      highlight-current-row
    >
      <el-table-column
        label="ID"
        width="100px"
      >
        <template slot-scope="scope">
          <b style="font-size: 1.5rem; line-height: 1.4; color: #0c212a">{{ scope.row.id }}</b>
        </template>
      </el-table-column>

      <el-table-column
        :label="$t('table.bill_no')"
        align="center"
      >
        <template slot-scope="scope">
          <el-tag type="info" effect="dark" style="font-size: 2rem; height: auto; line-height: 1.4">{{ scope.row.bill_no }}</el-tag>
        </template>
      </el-table-column>

      <el-table-column
        align="center"
      >
        <template slot="header" slot-scope="scope">
          <el-input
            v-model="search"
            size="mini"
            :placeholder="$t('table.bill_no')"
          />
        </template>
        <template slot-scope="scope">
          <el-button
            type="primary"
            size="medium"
            icon="el-icon-check"
            style="font-size: 2rem; margin: 1rem 0;"
            @click="handleComplete(scope.row, 'complete')"
          >
            {{ $t('actions.complete') }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>
  </el-row>
</template>

<script>
import Resource from '@/api/resource';
import { getStatusId } from '@/utils';
const BillResource = new Resource('hoa-don');
const StatusResource = new Resource('trang-thai');
export default {
  name: 'QueueList', props: {},
  data() {
    return {
      billList: [],
      statusList: {},
      search: '',
    };
  },
  created() {
    this.getStatusList()
      .then(x => this.getBillList());
    this.listenBill();
  },
  methods: {
    async getBillList() {
      const { data } = await BillResource.index({ status: getStatusId(this.statusList, 'calling') });
      this.billList = data;
    },
    async getStatusList() {
      const { data } = await StatusResource.index();
      this.statusList = data;
      return data;
    },
    listenBill() {
      const scope = this;
      window.Echo.channel('laravel_database_bill_pushed')
        .listen('.calling', (e) => {
          scope.billList.push(e.bill);
          scope.getBillList();
        });
    },
    handleComplete(bill, status) {
      BillResource.update(bill.id, { status_id: getStatusId(this.statusList, status), status: status })
        .then(response => {
          if (response.status === 200) {
            const index = this.billList.map(x => {
              return x.id;
            }).indexOf(bill.id);
            this.billList.splice(index, 1);

            // this.$notify({
            //   type: 'success',
            //   title: this.$t('status.success'),
            //   message: this.$t('table.bill_no') + ' ' + bill.bill_no + this.$t('notify.result.updated'),
            //   duration: 2000,
            // });
            this.getBillList();
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
  },

};
</script>

<style scoped>

</style>

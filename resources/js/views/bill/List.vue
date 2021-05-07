<template>
  <div class="app-container">
    <sticky :class-name="'sub-navbar draft'">
      <el-row justify="left" type="flex">
        <el-input
          v-model="exportOption.name"
          placeholder="File Name"
          clearable
          style="max-width: 20rem; margin-left: 0.5rem"
        />
        <div style="margin-left: 0.5rem; margin-right: 0.5rem">
          <status-dropdown v-model="exportOption['status']" :options="statusFilter" />
          <date-dropdown v-model="exportOption['date']" />
        </div>
        <div style="margin-left: 0.5rem; margin-right: 0.5rem">
          <el-button type="primary" size="small" icon="el-icon-download" :loading="downloadLoading" @click="handleDownload">Excel</el-button>
        </div>

        <div style="margin-left: auto">
          <el-button
            style="margin-left: 10px;"
            type="primary"
            @click="openCreateForm = true"
          >
            Tạo mới
          </el-button>
        </div>

      </el-row>
    </sticky>

    <bill-form v-if="openCreateForm" dialog-width="400px" :status-list="status" visible :title="$t('actions.create')" @onClose="onDialogClose" />
    <bill-form v-if="openEditForm" :bill-id="rowId" :status-list="status" dialog-width="400px" visible :title="$t('actions.edit')" @onClose="onDialogClose" />

    <el-table
      ref="multipleTable"
      v-loading="listLoading"
      :data="list"
      stripe
      border
      fit
      style="width: 100%"
      highlight-current-row
      @selection-change="handleSelectionChange"
    >
      <el-table-column
        type="selection"
        width="50"
        align="center"
      />

      <el-table-column
        align="center"
        :label="$t('table.id')"
        width="80"
      >
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('table.bill_no')" width="100">
        <template slot-scope="scope">
          <span style="color: #0a76a4; font-weight: bold; font-size: 16px">{{ scope.row.bill_no }}</span>
        </template>
      </el-table-column>

      <el-table-column
        align="center"
        :label="$t('table.status')"
        :filters="statusFilter"
        :filter-method="filterStatus"
      >
        <template slot-scope="scope">
          <el-tag :type="scope.row.status.display_level" effect="dark" size="medium">{{ $t('status.' + scope.row.status.name) }}</el-tag>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('table.created_at')" sortable prop="created_at">
        <template slot-scope="scope">
          <span>{{ scope.row.created_at | parseTime('{y}-{m}-{d} {h}:{i}') }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('table.updated_at')" sortable prop="updated_at">
        <template slot-scope="scope">
          <span>{{ scope.row.updated_at | parseTime('{y}-{m}-{d} {h}:{i}') }}</span>
        </template>
      </el-table-column>

      <el-table-column min-width="100px" align="center" :label="$t('table.actions')">
        <el-button-group slot-scope="scope">
          <el-button
            v-if="!isActive(scope.row.status.name, ['complete'])"
            type="primary"
            icon="el-icon-check"
            @click="onComplete(scope.row.id, statusFilter)"
          >
            {{ $t('actions.complete') }}
          </el-button>

          <el-button
            type="success"
            icon="el-icon-edit"
            @click="onEdit(scope.row.id)"
          >
            {{ $t('actions.edit') }}
          </el-button>

          <el-popconfirm
            confirm-button-text="Xác nhận"
            cancel-button-text="Đóng"
            cancel-button-type="danger"
            icon="el-icon-delete"
            icon-color="red"
            title="Hành động này sẽ xóa vĩnh viễn."
            @onConfirm="onDelete(scope.row.id)"
          >
            <el-button slot="reference" type="danger">{{ $t('actions.delete') }}</el-button>
          </el-popconfirm>

        </el-button-group>
      </el-table-column>
    </el-table>

    <el-row style="margin-top: 1rem;">
      <transition
        name="fade"
        @before-enter="beforeEnter"
        @enter="enter"
        @leave="leave"
      >
        <el-button-group v-if="isShowDeletesButton">
          <el-button type="danger" @click="onDeletes">{{ $t('actions.deleteSelection') }}</el-button>
          <el-button type="primary" @click="onCompletes">{{ $t('actions.completeSelection') }}</el-button>
        </el-button-group>
      </transition>

    </el-row>

    <pagination
      v-show="total>0"
      :total="total"
      :page.sync="listQuery.page"
      :limit.sync="listQuery.limit"
      @pagination="getList"
    />
  </div>
</template>

<script>
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import Resource from '@/api/resource';
import Sticky from '@/components/Sticky'; // Sticky header
import DateDropdown from './components/Dropdown/DateDropdown';
import StatusDropdown from './components/Dropdown/StatusDropdown';
import Velocity from 'velocity-animate';
import { parseTime } from '@/utils';
import BillForm from '@/views/bill/components/BillForm';

const BillResource = new Resource('hoa-don');
const StatusResource = new Resource('trang-thai');

export default {
  name: 'BillList',
  components: { BillForm, StatusDropdown, DateDropdown, Pagination, Sticky },
  data() {
    return {
      list: null,
      total: 0,
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 10,
      },
      statusFilter: [],
      status: null,
      exportOption: [
        date => [],
        status => [],
      ],
      downloadLoading: false,
      selectIds: [],
      multipleSelection: null,
      isShowDeletesButton: false,
      openCreateForm: false,
      openEditForm: false,
      rowId: null,
    };
  },
  created() {
    this.getList();
    this.getListStatus();
  },
  methods: {
    async getList() {
      this.listLoading = true;
      const { data, meta } = await BillResource.index(this.listQuery);
      this.list = data;
      this.total = meta.total;
      this.listLoading = false;
    },
    async getListStatus() {
      const scope = this;
      this.listLoading = true;
      const { data } = await StatusResource.index();
      this.status = data;
      this.listLoading = false;
      this.status.forEach(status => {
        scope.statusFilter.push({
          text: this.$t('level.' + status.name),
          value: status.name,
          id: status.id,
        });
      });
    },
    filterStatus(value, row) {
      return row.status.name === value;
    },
    isActive(scope, status) {
      return status.includes(scope);
    },
    onEdit(id) {
      this.rowId = id;
      this.openEditForm = true;
    },
    onComplete(id, status) {
      BillResource.update(id, { status_id: status.find((x) => x.value === 'complete').id })
        .then(response => {
          if (response.data) {
            this.$notify({
              title: this.$t('status.success'),
              message: response.data + this.$t('notify.result.edited'),
              type: 'success',
              duration: 2000,
            });
            this.getList();
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
    onDelete(id) {
      BillResource.destroy(id)
        .then(response => {
          if (response.data) {
            this.$notify({
              title: this.$t('status.success'),
              message: response.data + this.$t('notify.result.deleted'),
              type: 'success',
              duration: 2000,
            });
            this.getList();
          }
        })
        .catch(err => {
          this.$notify({
            title: 'Thất bại',
            message: err,
            type: 'error',
            duration: 2000,
          });
        });
    },
    onDeletes() {
      BillResource.destroys(this.multipleSelection)
        .then(response => {
          if (response.data) {
            this.$notify({
              title: this.$t('status.success'),
              message: this.$t('notify.result.multipleDeleted'),
              type: 'success',
              duration: 2000,
            });
            this.getList();
          }
        })
        .catch(err => {
          this.$notify({
            title: 'Thất bại',
            message: err,
            type: 'error',
            duration: 2000,
          });
        });
    },
    onCompletes() {
      const selectionData = JSON.parse(JSON.stringify(this.multipleSelection));
      const data = this.changeStatuses(selectionData, this.statusFilter, 'complete');

      BillResource.updates(data)
        .then(response => {
          if (response.status.toString() === 'success') {
            this.$notify({
              title: this.$t('status.success'),
              message: this.$t('notify.result.multipleUpdated'),
              type: 'success',
              duration: 2000,
            });
            this.getList();
          }
        })
        .catch(err => {
          this.$notify({
            title: 'Thất bại',
            message: err,
            type: 'error',
            duration: 2000,
          });
        });
    },
    changeStatuses(data, status, key) {
      data.forEach(row => {
        row.status = status.find(x => x.value === key).id;
      });
      return data;
    },
    onDialogClose(val) {
      this.openCreateForm = this.openEditForm = val;
      this.getList();
    },
    toggleSelection(rows) {
      if (rows) {
        rows.forEach(row => {
          this.$refs.multipleTable.toggleRowSelection(row);
        });
      } else {
        this.$refs.multipleTable.clearSelection();
      }
    },
    handleSelectionChange(val) {
      this.multipleSelection = val;
      this.isShowDeletesButton = val.length > 0;
    },
    handleDownload() {
      this.downloadLoading = true;
      if (
        this.multipleSelection !== null ||
        typeof this.exportOption.date !== 'undefined' ||
        typeof this.exportOption.status !== 'undefined'
      ) {
        // Export selection
        BillResource.index({
          date: this.exportOption.date,
          status: this.exportOption.status,
        })
          .then(response => {
            this.downloadLoading = true;
            // console.log(response.data);
            this.exportExcel(response.data);
            this.downloadLoading = false;
          });
      } else {
        BillResource.index()
          .then(response => {
            this.downloadLoading = true;
            // console.log(response.debug);
            this.exportExcel(response.data);
            this.downloadLoading = false;
          });
      }
    },
    exportExcel(_data) {
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = [this.$t('table.id'), this.$t('table.bill_no'), this.$t('table.status'), this.$t('table.description'), this.$t('table.created_at'), this.$t('table.updated_at')];
        const keys = ['id', 'bill_no', 'status', 'description', 'created_at', 'updated_at'];
        const data = this.formatJson(_data, keys);
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'kilo-' + parseTime(new Date()),
        });
        this.downloading = false;
      });
    },
    formatJson(jsonData, keys) {
      const scope = this;
      return jsonData.map(v => keys.map(k => {
        if (k === 'id') {
          return v.id;
        }
        if (k === 'bill_no') {
          return v.bill_no;
        }
        if (k === 'status') {
          return scope.$t('level.' + v.status.name);
        }
        if (k === 'description') {
          return v.status.description;
        }
        if (k === 'created_at') {
          return parseTime(v.created_at);
        }
        if (k === 'updated_at') {
          return parseTime(v.updated_at);
        }
      }));
    },
    beforeEnter: function(el) {
      el.style.opacity = 0;
      el.style.height = 0;
    },
    enter: function(el, done) {
      const delay = 150;
      setTimeout(function() {
        Velocity(
          el,
          { opacity: 1, height: '1em' },
          { complete: done }
        );
      }, delay);
    },
    leave: function(el, done) {
      const delay = 150;
      setTimeout(function() {
        Velocity(
          el,
          { opacity: 0, height: 0 },
          { complete: done }
        );
      }, delay);
    },
  },
};
</script>

<style scoped>

  .edit-input {
    padding-right: 100px;
  }

  .cancel-btn {
    position: absolute;
    right: 15px;
    top: 10px;
  }
</style>

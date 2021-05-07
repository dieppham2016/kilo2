<template>
  <div class="app-container">
    <sticky :class-name="'sub-navbar draft'">
      <el-row justify="left" type="flex">
        <el-input
          v-model="excelExportOption.name"
          placeholder="File Name"
          clearable
          style="max-width: 20rem; margin-left: 0.5rem"
        />
        <date-dropdown v-model="excelExportOption['date']" />
        <div style="margin-left: 0.5rem; margin-right: 0.5rem">
          <el-button
            type="primary"
            size="small"
            icon="el-icon-download"
            :loading="isDownloadLoading"
            @click="handleDownloadExcel"
          >Excel
          </el-button>
        </div>

        <div style="margin-left: auto">
          <el-button
            style="margin-left: 10px;"
            type="info"
            icon="el-icon-refresh"
            @click="getData"
          >
            {{ $t('sticky.button.refresh') }}
          </el-button>
        </div>

      </el-row>
    </sticky>

    <el-dialog
      v-if="showDetailItem"
      custom-class="wheel-changelog-detail-dialog"
      width="800px"
      center
      visible
      :before-close="handleCloseViewDetail"
    >
      <el-table :data="rowDetail">
        <el-table-column align="left" :label="$t('wheel.changeLog.popover.key')">
          <template slot-scope="scope">
            <span>{{ $t(scope.row.key) }}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" :label="$t('wheel.changeLog.popover.from')">
          <template slot-scope="scope">
            <span style="color: #7a818d; font-weight: bold">{{ scope.row.from }}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" :label="$t('wheel.changeLog.popover.to')">
          <template slot-scope="scope">
            <span style="color: #21bd71; font-weight: bold">{{ scope.row.to }}</span>
          </template>
        </el-table-column>
      </el-table>
    </el-dialog>

    <el-table
      ref="multipleTable"
      v-loading="isTableLoading"
      :data="data"
      border
      fit
      style="width: 100%"
      highlight-current-row
    >
      <el-table-column
        align="center"
        :label="$t('wheel.prize.table.label.id')"
        width="50"
      >
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('wheel.changeLog.table.label.username')">
        <template slot-scope="scope">
          <span style="color: #0a76a4; font-weight: bold; font-size: 16px">{{ scope.row.user_name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('wheel.changeLog.table.label.module')">
        <template slot-scope="scope">
          <el-tag type="primary">{{ scope.row.module }}</el-tag>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('wheel.changeLog.table.label.action')">
        <template slot-scope="scope">
          <el-tag :type="classAction(scope.row.action)">{{ scope.row.action }}</el-tag>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('wheel.changeLog.table.label.status')">
        <template slot-scope="scope">
          <el-tag :type="scope.row.status === 'Success' ? 'success' : 'danger' ">{{ scope.row.status }}</el-tag>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('wheel.changeLog.table.label.created_at')" sortable prop="created_at">
        <template slot-scope="scope">
          <span>{{ scope.row.created_at | parseTime('{y}-{m}-{d} {h}:{i}') }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('wheel.changeLog.table.label.updated_at')" sortable prop="updated_at">
        <template slot-scope="scope">
          <span>{{ scope.row.updated_at | parseTime('{y}-{m}-{d} {h}:{i}') }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('wheel.changeLog.table.label.option')" sortable prop="updated_at">
        <template slot-scope="scope">
          <el-button-group>
            <el-button
              icon="el-icon-view"
              type="success"
              @click="handleViewDetail(scope.row)"
            >{{ $t('wheel.changeLog.table.label.detail') }}
            </el-button>
          </el-button-group>
        </template>
      </el-table-column>

    </el-table>

    <pagination
      v-show="total>0"
      :total="total"
      :page.sync="query.page"
      :limit.sync="query.limit"
      @pagination="getData"
    />
  </div>
</template>

<script>

import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import Sticky from '@/components/Sticky'; // Sticky header
import DateDropdown from './components/DateDropdown';
import Resource from '@/api/resource';
import { parseTime } from '@/utils';

const ChangeLogResource = new Resource('wheel/change-log');

export default {
  name: 'WheelChangeLog',
  components: { Sticky, DateDropdown, Pagination },
  data() {
    return {
      total: 0,
      data: [],
      rowHover: {},
      rowDetail: [],
      showDetailItem: false,
      query: {
        page: 1,
        limit: 10,
      },
      excelExportOption: [
        date => [],
      ],
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
      const { data, meta } = await ChangeLogResource.index(this.query);
      this.data = data;
      this.total = meta.total;
      this.isTableLoading = false;
    },
    handleViewDetail(row, column, cell, event) {
      this.rowHover = row;
      this.rowDetail = this.createDetailData(row);
      this.showDetailItem = true;
    },
    handleCloseViewDetail() {
      this.showDetailItem = false;
    },
    createDetailData(row) {
      const result = [];
      if (row.action === 'UPDATE') {
        Object.keys(row.from).forEach((key, index) => {
          result.push({
            key: 'wheel.' + window._.camelCase(row.module) + '.table.label.' + key,
            from: row.from[key],
            to: row.to[key],
          });
        });
        return result;
      }
      if (row.action === 'CREATE') {
        Object.keys(row.to).forEach((key, index) => {
          result.push({
            key: 'wheel.' + window._.camelCase(row.module) + '.table.label.' + key,
            from: null,
            to: row.to[key],
          });
        });
        return result;
      }
      if (row.action === 'DELETE') {
        Object.keys(row.from).forEach((key, index) => {
          result.push({
            key: 'wheel.' + window._.camelCase(row.module) + '.table.label.' + key,
            from: row.from[key],
            to: null,
          });
        });
        return result;
      }
    },
    handleDownloadExcel() {
      this.isDownloadLoading = true;
      if (
        this.prizeSelectedList !== null ||
          typeof this.excelExportOption.date !== 'undefined'
      ) {
        // Export selection
        ChangeLogResource.index({
          date: this.excelExportOption.date,
        })
          .then(response => {
            this.isDownloadLoading = true;
            this.exportExcel(response.data);
          });
      } else {
        ChangeLogResource.index()
          .then(response => {
            this.isDownloadLoading = true;
            console.log(response.data);
            this.exportExcel(response.data);
          });
      }
    },
    exportExcel(prizes) {
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = [
          this.$t('wheel.changeLog.table.label.id'),
          this.$t('wheel.changeLog.table.label.username'),
          this.$t('wheel.changeLog.table.label.action'),
          this.$t('wheel.changeLog.table.label.from'),
          this.$t('wheel.changeLog.table.label.to'),
          this.$t('wheel.changeLog.table.label.status'),
          this.$t('wheel.changeLog.table.label.created_at'),
          this.$t('wheel.changeLog.table.label.updated_at')];
        const keys = ['id', 'user_name', 'action', 'from', 'to', 'status', 'created_at', 'updated_at'];
        const data = this.formatJson(prizes, keys);
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'wheel-change-log-' + parseTime(new Date()),
        });
        this.isDownloadLoading = false;
      });
    },
    formatJson(jsonData, keys) {
      return jsonData.map(v => keys.map(k => {
        if (k === 'id') {
          return v.id;
        }
        if (k === 'user_name') {
          return v.user_name;
        }
        if (k === 'action') {
          return v.action;
        }
        if (k === 'from') {
          return JSON.stringify(v.from);
        }
        if (k === 'to') {
          return JSON.stringify(v.to);
        }
        if (k === 'status') {
          return v.status;
        }
        if (k === 'created_at') {
          return parseTime(v.created_at);
        }
        if (k === 'updated_at') {
          return parseTime(v.updated_at);
        }
      }));
    },
    classAction(action) {
      switch (action) {
        case 'CREATE':
          return 'primary';
        case 'UPDATE':
          return 'info';
        default:
          return 'danger';
      }
    },
  },
};
</script>

<style lang="scss">
.wheel-changelog-detail-dialog {
  .el-dialog__header {
    display: none;
  }

  .el-dialog__body {
    padding: 5px;
    box-shadow: 2px 2px 0.5em rgba(122, 122, 122, 0.55),
    inset 1px 1px 0 rgba(255, 255, 255, 0.9),
    inset -1px -1px 0 rgba(0, 0, 0, 0.34);
    border: 1px solid #dedede;
    background: -moz-linear-gradient(-72deg, #dedede, #b5b5b5 16%, #dedede 21%, #d2d2d2 24%, #dedede 27%, #dedede 36%, #dadada 45%, #e8e8e8 60%, #dedede 72%, #d0cfcf 80%, #dedede 84%, #adadad);
    background: -webkit-linear-gradient(-72deg, #dedede, #b5b5b5 16%, #dedede 21%, #d2d2d2 24%, #dedede 27%, #dedede 36%, #dadada 45%, #e8e8e8 60%, #dedede 72%, #d0cfcf 80%, #dedede 84%, #adadad);
    background: -o-linear-gradient(-72deg, #dedede, #b5b5b5 16%, #dedede 21%, #d2d2d2 24%, #dedede 27%, #dedede 36%, #dadada 45%, #e8e8e8 60%, #dedede 72%, #d0cfcf 80%, #dedede 84%, #adadad);
    background: linear-gradient(-72deg, #dedede, #b5b5b5 16%, #dedede 21%, #d2d2d2 24%, #dedede 27%, #dedede 36%, #dadada 45%, #e8e8e8 60%, #dedede 72%, #d0cfcf 80%, #dedede 84%, #adadad);
  }

  .el-dialog__footer {
    display: none;
  }
}
</style>

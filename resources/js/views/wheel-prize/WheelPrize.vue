<template>
  <div class="app-container">
    <sticky :class-name="'sub-navbar draft'">
      <el-row justify="left" type="flex">
        <el-input
          v-model="prizeExportOption.name"
          placeholder="File Name"
          clearable
          style="max-width: 20rem; margin-left: 0.5rem"
        />
        <div style="margin-left: 0.5rem; margin-right: 0.5rem">
          <el-button
            type="primary"
            size="small"
            icon="el-icon-download"
            :loading="isDownloadLoading"
            @click="handleDownloadPrizes"
          >Excel
          </el-button>
        </div>

        <div style="margin-left: auto">
          <el-button
            style="margin-left: 10px;"
            type="info"
            icon="el-icon-refresh"
            @click="getPrizeList"
          >
            {{ $t('sticky.button.refresh') }}
          </el-button>
          <el-button
            style="margin-left: 10px;"
            type="primary"
            icon="el-icon-plus"
            @click="isShowWheelPrizeForm = true"
          >
            {{ $t('sticky.button.create') }}
          </el-button>

        </div>

      </el-row>
    </sticky>

    <wheel-prize-form v-if="isShowWheelPrizeForm" :prize="rowUpdate" visible dialog-width="400px" @onClose="onWheelPrizeFormClose" />

    <el-table
      ref="multipleTable"
      v-loading="isTableLoading"
      :data="prizes"
      border
      fit
      style="width: 100%"
      highlight-current-row
      @selection-change="handleSelectionPrize"
    >
      <el-table-column
        type="selection"
        width="50"
        align="center"
      />

      <el-table-column
        align="center"
        :label="$t('wheel.prize.table.label.id')"
        width="50"
      >
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>

      <el-table-column
        align="center"
        :label="$t('wheel.prize.table.label.pattern')"
        :filters="patternFilterList"
        :filter-method="patternFilter"
      >
        <template slot-scope="scope">
          <el-tag
            v-for="pattern in scope.row.prize_patterns"
            :key="pattern.id"
            type="success"
            effect="dark"
            size="medium"
            style="margin-right: 5px"
          >{{ pattern.name }}</el-tag>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('wheel.prize.table.label.name')">
        <template slot-scope="scope">
          <span style="color: #0a76a4; font-weight: bold; font-size: 16px">{{ scope.row.name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('wheel.prize.table.label.type')" width="100">
        <template slot-scope="scope">
          <el-tag effect="dark" :type="scope.row.type == 'Code' ? 'success' : 'primary'">{{ scope.row.type }}</el-tag>
        </template>
      </el-table-column>

      <!--      <el-table-column align="center" :label="$t('wheel.prize.table.label.size')" width="100">-->
      <!--        <template slot-scope="scope">-->
      <!--          <span class="text-bold-16" :style="{color: '#0a76a4'}">{{ scope.row.size }}</span>-->
      <!--        </template>-->
      <!--      </el-table-column>-->

      <el-table-column align="center" :label="$t('wheel.prize.table.label.rate')" width="120">
        <template slot-scope="scope">
          <span class="text-bold-16" :style="{color: '#0a76a4'}">{{ scope.row.rate }}%</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('wheel.prize.table.label.more_info')">
        <template slot-scope="scope">
          <span class="text-bold-16" :style="{color: '#0a76a4'}">{{ scope.row.more_info }}</span>
        </template>
      </el-table-column>

      <el-table-column min-width="100px" width="300px" align="center" :label="$t('wheel.prize.table.action')">
        <el-button-group slot-scope="scope">
          <el-button icon="el-icon-edit" type="success" @click="handleUpdatePrize(scope.row)">{{ $t('wheel.prize.table.actions.edit') }}</el-button>
          <el-popconfirm
            :confirm-button-text="$t('wheel.prize.table.actions.confirm')"
            :cancel-button-text="$t('wheel.prize.table.actions.cancel')"
            cancel-button-type="secondary"
            confirm-button-type="danger"
            icon="el-icon-delete"
            icon-color="red"
            :title="$t('wheel.prize.table.actions.delete.confirm')"
            @onConfirm="handleDeletePrize(scope.row.id)"
          >
            <el-button slot="reference" icon="el-icon-edit" type="danger">{{ $t('wheel.prize.table.actions.delete.label') }}</el-button>
          </el-popconfirm>

        </el-button-group>
      </el-table-column>
    </el-table>

    <el-row style="margin-top: 1rem;">
      <transition
        name="fade"
        @before-enter="beforeTableShowMultipleAction"
        @enter="showTableMultipleAction"
        @leave="hideTableMultipleAction"
      >
        <el-button-group v-if="isShowTableMultipleActionDeleteButton">
          <el-popconfirm
            :confirm-button-text="$t('wheel.prize.table.actions.confirm')"
            :cancel-button-text="$t('wheel.prize.table.actions.cancel')"
            cancel-button-type="secondary"
            confirm-button-type="danger"
            icon="el-icon-delete"
            icon-color="red"
            :title="$t('wheel.prize.table.actions.delete.confirm')"
            @onConfirm="handleDeletePrizes()"
          >
            <el-button slot="reference" type="danger">{{ $t('wheel.prize.table.actions.delete.label') }}</el-button>
          </el-popconfirm>
        </el-button-group>
      </transition>

    </el-row>
    <pagination
      v-show="prizeTotal>0"
      :total="prizeTotal"
      :page.sync="prizeQuery.page"
      :limit.sync="prizeQuery.limit"
      @pagination="getPrizeList"
    />
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import WheelPrizeForm from '@/views/wheel-prize/components/WheelPrizeForm';
import Resource from '@/api/resource';
import Velocity from 'velocity-animate';
import Sticky from '@/components/Sticky';
import { parseTime } from '@/utils';

const PrizeResource = new Resource('wheel/prizes');

export default {
  name: 'WheelPrize',
  components: { Sticky, WheelPrizeForm, Pagination },
  data() {
    return {
      prizes: [],
      prizeSelectedList: [],
      patternFilterList: [],
      prizeQuery: {
        page: 1,
        limit: 10,
      },
      prizeExportOption: [
        date => [],
        pattern => [],
      ],
      rowUpdate: null,
      prizeTotal: 0,
      isTableLoading: false,
      isDownloadLoading: false,
      isShowWheelPrizeForm: false,
      isShowTableMultipleActionDeleteButton: false,
    };
  },
  created() {
    this.getPrizeList();
  },
  methods: {
    async getPrizeList() {
      this.isTableLoading = true;
      const { data, meta } = await PrizeResource.index(this.prizeQuery);
      this.prizes = data;
      this.prizeTotal = meta.total;
      const pattenList = [];
      data.forEach((row) => {
        row.prize_patterns.forEach((e) => {
          pattenList.push({
            text: e.name,
            value: e.alias,
          });
        });
      });
      this.patternFilterList = window._.unionWith(pattenList, window._.isEqual);
      this.isTableLoading = false;
    },
    onWheelPrizeFormClose(val) {
      this.isShowWheelPrizeForm = val;
      this.rowUpdate = null;
      this.getPrizeList();
    },
    handleSelectionPrize(prizesSelected) {
      this.prizeSelectedList = prizesSelected;
      this.isShowTableMultipleActionDeleteButton = prizesSelected.length > 0;
    },
    handleUpdatePrize(prize) {
      this.rowUpdate = prize;
      this.isShowWheelPrizeForm = true;
    },
    handleDeletePrize(id) {
      PrizeResource.destroy(id).then(response => {
        if (response.status === 200) {
          this.$notify({
            title: this.$t('status.success'),
            message: response.data.message + this.$t('notify.result.deleted'),
            type: 'success',
            duration: 2000,
          });
          this.getPrizeList();
        } else if (response.status === 404) {
          this.$notify({
            title: this.$t('status.failure'),
            message: this.$t('notify.' + response.data.message),
            type: 'success',
            duration: 2000,
          });
        }
      }).catch(err => {
        this.$notify({
          title: this.$t('status.failure'),
          message: err,
          type: 'error',
          duration: 2000,
        });
      });
    },
    handleDeletePrizes() {
      const ids = this.prizeSelectedList.map(row => row.id);
      PrizeResource.destroys(ids).then(response => {
        if (response.status === 200) {
          this.$notify({
            title: this.$t('status.success'),
            message: this.$t('notify.' + response.data.message),
            type: 'success',
            duration: 2000,
          });
          this.getPrizeList();
        } else if (response.status === 404) {
          this.$notify({
            title: this.$t('status.failure'),
            message: this.$t('notify.' + response.data.message),
            type: 'success',
            duration: 2000,
          });
        }
      }).catch(err => {
        this.$notify({
          title: this.$t('status.failure'),
          message: err,
          type: 'error',
          duration: 2000,
        });
      });
    },
    handleDownloadPrizes() {
      this.isDownloadLoading = true;
      if (
        this.prizeSelectedList !== null ||
          typeof this.prizeExportOption.date !== 'undefined' ||
          typeof this.prizeExportOption.pattern !== 'undefined'
      ) {
        // Export selection
        PrizeResource.index({
          date: this.prizeExportOption.date,
          pattern: this.prizeExportOption.pattern,
        })
          .then(response => {
            this.isDownloadLoading = true;
            this.exportExcel(response.data);
          });
      } else {
        PrizeResource.index()
          .then(response => {
            this.isDownloadLoading = true;
            this.exportExcel(response.data);
          });
      }
    },
    exportExcel(prizes) {
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = [
          this.$t('wheel.prize.table.label.id'),
          this.$t('wheel.prize.table.label.name'),
          this.$t('wheel.prize.table.label.type'),
          this.$t('wheel.prize.table.label.amount'),
          this.$t('wheel.prize.table.label.rate'),
          this.$t('wheel.prize.table.label.moreInfo')];
        const keys = ['id', 'name', 'type', 'amount', 'rate', 'more_info'];
        const data = this.formatJson(prizes, keys);
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'wheel-prize-' + parseTime(new Date()),
        });
        this.isDownloadLoading = false;
      });
    },
    formatJson(jsonData, keys) {
      return jsonData.map(v => keys.map(k => {
        if (k === 'id') {
          return v.id;
        }
        if (k === 'name') {
          return v.name;
        }
        if (k === 'type') {
          return v.type;
        }
        if (k === 'amount') {
          return v.amount;
        }
        if (k === 'rate') {
          return v.rate;
        }
        if (k === 'more_info') {
          return v.more_info;
        }
      }));
    },
    patternFilter(value, row) {
      return row.prize_patterns.map(v => v.alias === value).some(v => v === true);
    },
    beforeTableShowMultipleAction(el) {
      el.style.opacity = 0;
      el.style.height = 0;
    },
    showTableMultipleAction(el, done) {
      const delay = 150;
      setTimeout(function() {
        Velocity(
          el,
          { opacity: 1, height: '1em' },
          { complete: done }
        );
      }, delay);
    },
    hideTableMultipleAction(el, done) {
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

<style >
.text-bold-16 {
  font-weight: bold;
  font-size: 16px;
}
.rectangle-20 {
  width: 20px;
  height: 20px;
  display: inline-block;
  margin: auto;
}
.el-table .danger-row {
  background: #edc1c1;
}
</style>

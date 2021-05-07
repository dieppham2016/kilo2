<template>
  <div class="app-container">
    <sticky :class-name="'sub-navbar draft'">
      <el-row justify="left" type="flex">
        <div style="margin-left: auto">
          <el-button
            style="margin-left: 10px;"
            type="info"
            icon="el-icon-refresh"
            @click="getPatternList"
          >
            {{ $t('sticky.button.refresh') }}
          </el-button>
          <el-button
            style="margin-left: 10px;"
            type="primary"
            icon="el-icon-plus"
            @click="isShowWheelPatternForm = true"
          >
            {{ $t('sticky.button.create') }}
          </el-button>

        </div>

      </el-row>
    </sticky>

    <wheel-pattern-form v-if="isShowWheelPatternForm" :pattern="rowUpdate" visible @onClose="onWheelPatternFormClose" />
    <el-table
      ref="patternTable"
      v-loading="isTableLoading"
      :data="patterns"
      stripe
      border
      fit
      style="width: 100%"
      highlight-current-row
      @selection-change="handleSelectionPattern"
    >
      <el-table-column
        type="selection"
        width="50"
        align="center"
      />

      <el-table-column align="center" :label="$t('wheel.pattern.table.label.name')">
        <template slot-scope="props">
          <span class="text-bold-16" style="color: #0a76a4;">{{ props.row.name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('wheel.pattern.table.label.alias')">
        <template slot-scope="props">
          <span class="text-bold-16" :style="{color: '#0a76a4'}">{{ props.row.alias }}</span>
        </template>
      </el-table-column>

      <el-table-column min-width="100px" width="300px" align="center" :label="$t('wheel.pattern.table.label.action')">
        <el-button-group slot-scope="props">
          <el-button icon="el-icon-edit" type="success" @click="handleUpdatePattern(props.row)">
            {{ $t('wheel.pattern.table.actions.edit') }}
          </el-button>
          <el-popconfirm
            :confirm-button-text="$t('wheel.pattern.table.actions.confirm')"
            :cancel-button-text="$t('wheel.pattern.table.actions.cancel')"
            cancel-button-type="secondary"
            confirm-button-type="danger"
            icon="el-icon-delete"
            icon-color="red"
            :title="$t('wheel.pattern.table.actions.delete.confirm')"
            @onConfirm="handleDeletePattern(props.row.id)"
          >
            <el-button slot="reference" type="danger">{{ $t('wheel.pattern.table.actions.delete.label') }}</el-button>
          </el-popconfirm>

        </el-button-group>
      </el-table-column>
    </el-table>

    <pagination
      v-show="patternTotal>0"
      :total="patternTotal"
      :page.sync="patternQuery.page"
      :limit.sync="patternQuery.limit"
      @pagination="getPatternList"
    />
  </div>
</template>

<script>
import Sticky from '@/components/Sticky';
import Pagination from '@/components/Pagination';
import { parseTime } from '@/utils';
import Velocity from 'velocity-animate';
import Resource from '@/api/resource';
import WheelPatternForm from '@/views/wheel-pattern/components/WheelPatternForm';

const PatternResource = new Resource('wheel/patterns');

export default {
  name: 'WheelPattern',
  components: { WheelPatternForm, Sticky, Pagination },
  data() {
    return {
      patterns: [],
      patternSelectedList: [],
      patternQuery: {
        page: 1,
        limit: 10,
      },
      patternExportOption: [
        date => [],
        collection => [],
      ],
      rowUpdate: null,
      patternTotal: 0,
      isTableLoading: false,
      isDownloadLoading: false,
      isShowWheelPatternForm: false,
      isShowTableMultipleActionDeleteButton: false,
    };
  },
  created() {
    this.getPatternList();
  },
  methods: {
    async getPatternList() {
      this.isTableLoading = true;
      const { data, meta } = await PatternResource.index(this.patternQuery);
      this.patterns = data;
      this.patternTotal = meta.total;
      this.isTableLoading = false;
    },
    onWheelPatternFormClose(val) {
      this.getPatternList();
      this.isShowWheelPatternForm = val;
      this.rowUpdate = null;
    },
    toggleExpand(row) {
      this.$refs.patternTable.toggleRowExpansion(row);
    },
    handlePatternConfigChange(id, prizeLocation) {

    },
    handleSelectionPattern(patternSelected) {
      this.prizeSelectedList = patternSelected;
      this.isShowTableMultipleActionDeleteButton = patternSelected.length > 0;
    },
    handleUpdatePattern(pattern) {
      this.rowUpdate = pattern;
      this.isShowWheelPatternForm = true;
    },
    handleDeletePattern(id) {
      PatternResource.destroy(id).then(response => {
        if (response.status === 200) {
          this.$notify({
            title: this.$t('status.success'),
            message: response.data.message + this.$t('notify.result.deleted'),
            type: 'success',
            duration: 2000,
          });
          this.getPatternList();
        } else if (response.status === 500) {
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
    handleDeletePatterns() {
      const ids = this.patternSelectedList.map(row => row.id);
      PatternResource.destroys(ids).then(response => {
        if (response.status === 200) {
          this.$notify({
            title: this.$t('status.success'),
            message: this.$t('notify.' + response.data.message),
            type: 'success',
            duration: 2000,
          });
          this.getPatternList();
        } else if (response.status === 500) {
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
    handleDownloadPattern() {
      this.isDownloadLoading = true;
      if (
        this.patternSelectedList !== null ||
          typeof this.patternExportOption.date !== 'undefined'
      ) {
        // Export selection
        PatternResource.index({
          date: this.patternExportOption.date,
        })
          .then(response => {
            this.isDownloadLoading = true;
            this.exportExcel(response.data);
          });
      } else {
        PatternResource.index()
          .then(response => {
            this.isDownloadLoading = true;
            this.exportExcel(response.data);
          });
      }
    },
    exportExcel(pattern) {
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = [
          this.$t('wheel.prize.table.label.id'),
          this.$t('wheel.prize.table.label.name'),
          this.$t('wheel.prize.table.label.alias')];
        const keys = ['id', 'name', 'alias'];
        const data = this.formatJson(pattern, keys);
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'wheel-pattern-' + parseTime(new Date()),
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
        if (k === 'alias') {
          return v.amount;
        }
      }));
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

<style scoped>

</style>

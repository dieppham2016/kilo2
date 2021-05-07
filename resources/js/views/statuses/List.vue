<template>
  <div class="app-container">
    <sticky :class-name="'sub-navbar draft'">
      <el-row>
        <el-button
          style="margin-left: 10px;"
          type="primary"
          @click="onCreate"
        >
          {{ $t('actions.create') }}
        </el-button>
      </el-row>
    </sticky>

    <status-form dialog-width="400px" :visible="createDialogVisible" :title="$t('actions.create')" @onClose="onDialogClose" />
    <status-form dialog-width="400px" :status-id="rowId" :visible="editDialogVisible" :title="$t('actions.edit')" @onClose="onDialogClose" />

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
        width="50"
      >
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>

      <el-table-column
        align="center"
        :label="$t('table.status')"
        width="150px"
      >
        <template slot-scope="scope">
          <el-tag effect="plain" size="medium">{{ scope.row.name }}</el-tag>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('level.title')" width="150px">
        <template slot-scope="scope">
          <el-tag :type="scope.row.display_level" effect="dark" size="medium">{{ $t('level.' + scope.row.display_level) }}</el-tag>
        </template>
      </el-table-column>

      <el-table-column align="center" :label="$t('table.description')">
        <template slot-scope="scope">
          <span>{{ scope.row.description }}</span>
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
            type="success"
            icon="el-icon-edit"
            @click="onEdit(scope.row.id)"
          >
            {{ $t('actions.edit') }}
          </el-button>
          <el-popconfirm
            :confirm-button-text="$t('actions.confirm')"
            :cancel-button-text="$t('actions.close')"
            cancel-button-type="danger"
            icon="el-icon-delete"
            icon-color="red"
            :title="$t('confirm.delete')"
            @onConfirm="onDelete(scope.row.id)"
          >
            <el-button slot="reference" type="danger">{{ $t('actions.delete') }}</el-button>
          </el-popconfirm>

        </el-button-group>
      </el-table-column>
    </el-table>

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
import StatusForm from '@/views/statuses/components/StatusForm';

const StatusResource = new Resource('trang-thai');

export default {
  name: 'StatusList',
  components: { StatusForm, Pagination, Sticky },
  data() {
    return {
      list: null,
      total: 0,
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 10,
      },
      createDialogVisible: false,
      editDialogVisible: false,
      rowId: null,
    };
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      this.listLoading = true;
      const { data, meta } = await StatusResource.index(this.listQuery);
      this.list = data;
      this.total = meta.total;
      this.listLoading = false;
    },
    onEdit(id) {
      this.rowId = id;
      this.editDialogVisible = true;
    },
    onCreate() {
      this.createDialogVisible = true;
    },
    onDelete(id) {
      StatusResource.destroy(id)
        .then(response => {
          if (response.status === 200) {
            this.$notify({
              title: this.$t('status.success'),
              message: this.$t('status.updated'),
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
    onDialogClose(val) {
      this.createDialogVisible = this.editDialogVisible = val;
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

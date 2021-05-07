<template>
  <div>

    <sticky :class-name="'sub-navbar draft'">
      <el-row type="flex" justify="left">
        <div style="margin-left: auto">
          <el-button
            style="margin-left: auto;"
            type="success"
            @click="restart"
          >
            {{ $t('actions.restart') }}
          </el-button>
          <el-button
            style="margin-left: auto;"
            type="danger"
            @click="shutdown"
          >
            {{ $t('actions.shutdown') }}
          </el-button>
        </div>
      </el-row>
    </sticky>
    <el-row style="padding: 0 15px; background: #656565" type="flex" justify="center">
      <el-col :xs="24" :sm="24" :md="11">
        <span class="title">Hóa đơn chờ ra món</span>
        <queue-calling />
        <queue-create />
      </el-col>
      <el-col :md="1" />
      <el-col :xs="24" :sm="24" :md="11">
        <span class="title">Hóa đơn chờ khách lấy</span>
        <queue-list />
      </el-col>
    </el-row>
  </div>
</template>

<script>
import QueueCreate from '@/views/queue-manager/components/QueueCreate';
import QueueList from '@/views/queue-manager/components/QueueList';
import QueueCalling from '@/views/queue-manager/components/QueueCalling';
import Sticky from '@/components/Sticky';

export default {
  name: 'QueueManager',
  components: { Sticky, QueueCalling, QueueList, QueueCreate },
  methods: {
    restart() {
    },
    shutdown() {
    },
  },
};
</script>

<style scoped>
.title {
  width: 100%;
  display: block;
  margin: 0.5em 0 0 0;
  font-size: 2em;
  border-radius: 5px;
  text-align: center;
  padding: 15px 20px;
  text-shadow: 2px 2px 2px #1F91FF;
  color: #FFFFFF;
  background: #44ADC1;
}
</style>

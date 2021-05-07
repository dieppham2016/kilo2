<template>
  <el-row type="flex" justify="center">
    <div class="queue-container">
      <div class="queue-wrapper" :style="{width: viewWidth + 'px'}">

        <el-carousel
          arrow="never"
          indicator-position="none"
          trigger="click"
          :initial-index="1"
          :interval="5000"
          :height="viewHeight + 'px'"
          direction="vertical"
          :autoplay="true"
        >
          <el-carousel-item v-for="list in showList" :key="list.toString() + '_' + Math.random().toString(33).substring(7)">
            <ul class="queue--list-carousel">
              <li
                v-for="(item, index) in list"
                :key="item.toString() + '_' + Math.random().toString(33).substring(7)"
                class="queue--list-carousel--item"
                :style="{height: listHeight + 'px', 'line-height': listHeight * alignText[index] + 'px', width: viewWidth + 'px'}"
              >
                <span class="large i--1" :style="{'font-size': listHeight * 0.6 + 'px'}">{{ item.bill_no }}</span>
              </li>
            </ul>

          </el-carousel-item>
        </el-carousel>
      </div>
    </div>
  </el-row>
</template>
<script>
import Resource from '@/api/resource';
import { getStatusId } from '@/utils';
import Velocity from 'velocity-animate';

const BillResource = new Resource('hoa-don');
const StatusResource = new Resource('trang-thai');

export default {
  name: 'QueueView2',
  props: {
    viewHeight: {
      type: Number,
      default: 0,
    },
    viewWidth: {
      type: Number,
      default: 0,
    },
    limitList: {
      type: Number,
      default: 4,
    },
  },
  data() {
    return {
      billList: [],
      showList: [],
      alignText: [1.26, 1.1, 1, 0.8],
    };
  },
  computed: {
    listHeight() {
      //  + '_' + Math.random().toString(33).substring(7)
      return this.viewHeight / this.limitList;
    },
  },
  watch: {
    billList(data) {
      this.showList = [];
      for (let i = 0, j = data.length; i < j; i += 4) {
        this.showList.push(data.slice(i, i + 4));
      }
    },
  },
  created() {
    this.getStatusList()
      .then(x => {
        return this.getBillList();
      });
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
    },
    listenBill() {
      const scope = this;
      window.Echo.channel('laravel_database_bill_pushed')
        .listen('.calling', (e) => {
          const parts = e.bill.bill_no.match(/./g);
          e.bill.bill_no = parts.join(' ');
          scope.billList.push(e.bill);
        })
        .listen('.complete', e => {
          const index = scope.billList.map(x => {
            return x.id;
          }).indexOf(e.bill.id);
          scope.billList.splice(index, 1);
        });
    },
  },
};
</script>

<style lang="scss" scoped>

.queue-container {
  z-index: 10000;
  .queue-wrapper {
    position: relative;
    .queue--list-carousel {
      width: 100%;
      padding: 0;
      margin: 0;
      list-style: none;
      .queue--list-carousel--item {
        padding: 0;
        float: right;
        width: 100%;
        text-align: center;
        .large {
          width: 100%;
          align-content: center;
          font-weight: bold;
          text-shadow: 4px 1px 8px #2E6ACE, 4px 1px 8px #2E6ACE, 4px 1px 8px #2E6ACE;
          color: #FFFFFF;
        }

        .i--1 {
        }
      }
    }
  }
}

</style>

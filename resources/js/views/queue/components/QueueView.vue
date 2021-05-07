<template>
  <el-row type="flex" justify="center">
    <transition-group
      class="queue--wrapper"
      name="staggered-fade"
      tag="ul"
      :css="true"
      @before-enter="beforeEnter"
      @enter="enter"
      @leave="leave"
    >

      <li v-for="item in billList" :key="item.id" :data-index="item.id" class="queue--item" :style="listStyle">
        {{ item.bill_no }}
      </li>
    </transition-group>
  </el-row>
</template>
<script>
import Resource from '@/api/resource';
import { getStatusId } from '@/utils';
import Velocity from 'velocity-animate';

const BillResource = new Resource('hoa-don');
const StatusResource = new Resource('trang-thai');

export default {
  name: 'QueueView',
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
      default: 5,
    },
  },
  data() {
    return {
      billList: [],
    };
  },
  computed: {
    getListHeight() {
      return this.viewHeight / this.limitList;
    },
    getFontSizeOfList() {
      return this.getListHeight - (this.getListHeight * 0.3) + 'px';
    },
    listStyle() {
      return {
        'line-height': this.getListHeight + 'px',
        'font-size': this.getFontSizeOfList,
      };
    },
  },
  watch: {
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
      const { data } = await BillResource.index({ status: getStatusId(this.statusList, 'waiting') });
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
    beforeEnter(el) {
      el.style.opacity = 0;
      el.style.height = 0;
    },
    enter(el, done) {
      const delay = 50;
      const scope = this;
      setTimeout(function() {
        Velocity(
          el,
          { opacity: 1, height: scope.viewHeight / scope.limitList },
          { complete: done }
        );
      }, delay);
    },
    leave(el, done) {
      var delay = 50;
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

<style lang="scss" scoped>
  .queue--wrapper {
    border: 1px solid #ffffff;
    box-shadow: 1px 1px 5px #cccccc;
    width: 100%;
    padding: 0;
    margin: 0;
    list-style: none;
    .queue--item {
      width: 100%;
      text-align: center;
      color: #dfdfdf;
      background: rgb(0, 0, 0);
      background: linear-gradient(90deg, rgb(0, 0, 0) 0%, rgb(0, 0, 0) 50%, rgb(0, 0, 0) 100%);
      border: 1px solid #ffffff;
    }
  }
</style>

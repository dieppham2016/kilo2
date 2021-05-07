<template>
  <el-col :style="{background: '#0a76a4', height: _windowHeight}">
    <div class="lucky-wheel--background--bottom" />

    <top-bar :bonus-enable="bonusEnable" :time-play="timePlay" :current-bonus="bonus" />

    <el-row :style="{ height: _windowHeight}">
      <wheel ref="wheel" :segments="segments" :max-segment="segments.length" @spinStop="handleSpinStop" />
    </el-row>

    <bottom-bar :error="error" :credit="storeGame.coin" :ticket="ticketReturn" @handleStart="startPin" />

    <win-prize-dialog :is-show-winning-dialog="isShowWinningDialog" :prize-winning="prizeWinning" @onClosed="handleCloseWinDialog" />

    <demo-dialog :error="error" :is-show-demo-dialog="isShowDemoDialog" :ticket="ticketReturn" :credit="storeGame.coin" @addCoin="handleAddCoin" @onClosed="handleCloseDemoDialog" />
  </el-col>
</template>

<script>
import Wheel from '@/views/wheel/components/Wheel';
import Resource from '@/api/resource';
import TopBar from '@/views/wheel/components/TopBar';
import BottomBar from '@/views/wheel/components/BottomBar';
import WinPrizeDialog from '@/views/wheel/components/WinPrizeDialog';
import DemoDialog from '@/views/wheel/components/DemoDialog';

const PatternResource = new Resource('wheel/patterns');
const ConfigResource = new Resource('wheel/config');
const StoreResource = new Resource('wheel/store');

export default {
  name: 'LuckyWheel',
  components: { DemoDialog, WinPrizeDialog, BottomBar, TopBar, Wheel },
  data() {
    return {
      timePlay: 0,
      coin: 0,
      bonus: 0,
      timeAutoplay: null,
      patternData: [],
      degrees: null,
      config: [],
      storeGame: [],
      segments: [],
      ticketReturn: 0,
      error: '',
      timeReturnTicket: null,
      bonusEnable: false,
      wheelLoading: false,
      isWheelReady: true,
      isWheelRunning: false,
      isShowWinningDialog: false,
      isShowDemoDialog: true,
      prizeWinning: null,
      windowHeight: window.innerHeight,
      windowWidth: window.innerWidth,
    };
  },
  sockets: {
    connect() {
      // Fired when the socket connects.
      console.log('connect Socket');
      this.$socket.emit('activeTicket', Number(!this.config.io_ticket_output));
    },

    disconnect() {
      console.log('disconnect Socket');
    },
    // Fired when the server sends something on the "messageChannel" channel.
    messageChannel(data) {
      // console.log(data);
    },
    buttonStartPressed(level) {
      if (this.config && level === this.config.io_ticket_input) {
        if (this.isShowDemoDialog && this.storeGame.coin > 0) {
          setTimeout(() => {
            this.isShowDemoDialog = false;
            this.countDown();
          }, 200);
        } else {
          this.startPin();
        }
      }
    },
    coinInput(level) {
      if (this.config && level === this.config.io_ticket_input) {
        this.handleAddCoin();
      }
    },
    ticketInput(level) {
      if (this.config && level === this.config.io_ticket_input) {
        this.storeGame.ticketRemaining = this.ticketReturn;
        if (this.ticketReturn > 0) {
          this.ticketReturn--;
          if (this.error) {
            this.error = '';
          }
        } else {
          this.disableSendTicket();
        }
      }
    },
    clearTicket(level) {
      if (this.config && level === this.config.io_ticket_input) {
        if (this.timeReturnTicket) {
          clearInterval(this.timeReturnTicket);
        }
        this.checkTicketRemaining();
        this.error = '';
        setTimeout(() => {
          StoreResource.update(1, {
            current_error: this.error,
          }, 1000);
        });
      }
    },
  },
  computed: {
    _windowWidth() {
      return this.windowWidth + 'px';
    },
    _windowHeight() {
      return this.windowHeight + 'px';
    },
  },
  watch: {
    ticketReturn(newValue, oldValue) {
      if (newValue !== oldValue) {
        if (this.timeReturnTicket) {
          clearInterval(this.timeReturnTicket);
        }
        if (newValue > 0) {
          this.timeReturnTicket = setTimeout(() => {
            this.$socket.emit('activeTicket', Number(!this.config.io_ticket_output));
            console.log('Error Ticket');
            this.error = 'Liên hệ nhân viên';
            StoreResource.update(1, {
              ticket_remaining_by_session: this.storeGame.ticket_remaining_by_session,
              current_error: this.error,
              ticket_remaining: this.ticketReturn,
            });
          }, 1000);
        } else if (newValue <= 0) {
          if (this.timeReturnTicket) {
            clearInterval(this.timeReturnTicket);
            StoreResource.update(1, {
              ticket_remaining_by_session: this.storeGame.ticket_remaining_by_session,
              current_error: this.error,
              ticket_remaining: this.ticketReturn,
            }).then(() => {
              setTimeout(() => {
                this.checkTicketRemaining();
              }, 1000);
            });
          }
        }
      }
    },
  },
  created() {
    this.getPatternData();
    this.getConfig();
    this.getStoreGame();
    this.$socket.disconnect();
    this.$socket.connect();
  },
  mounted() {
    this.$nextTick(() => {
      window.addEventListener('resize', this.onResize);
    });
  },
  methods: {
    async getPatternData() {
      this.wheelLoading = true;
      const { data } = await PatternResource.index();
      this.patternData = data;
      this.bonusEnable = !!this.patternData[0].bonus_enable;
      if (this.bonusEnable) {
        this.bonus = this.patternData[0].bonus_auto_increment ? this.patternData[0].bonus_current : this.patternData[0].bonus_fixed;
      }
      this.wheelLoading = false;
      this.segments = this.detachSegments();
    },
    async getConfig() {
      const { data } = await ConfigResource.index();
      this.config = data[0];
      this.timePlay = this.mills2second(this.config.time_auto_play);
    },
    async getStoreGame() {
      const { data } = await StoreResource.index();
      this.storeGame = data[0];
      if (!this.ticketReturn) {
        this.checkTicketRemaining();
      }
      this.error = this.storeGame.current_error;
    },
    getBonusData(callback) {
      PatternResource.index({ only: ['bonus_current', 'bonus_enable'] }).then((response) => {
        if (response.data[0].bonus_enable) {
          this.bonus = response.data[0].bonus_current;
        }
        if (callback) {
          callback();
        }
      });
    },
    detachSegments() {
      const canvas = document.getElementById('canvas');
      const context = canvas.getContext('2d');
      const gradientRed = context.createRadialGradient(this.windowHeight / 1.3, this.windowHeight / 1.3, this.windowHeight / 1.3, 0, 0, 0);
      const gradientGold = context.createRadialGradient(this.windowHeight / 1.3, this.windowHeight / 1.3, this.windowHeight / 1.3, 0, 0, 0);

      gradientRed.addColorStop(0, '#cf0834');
      gradientRed.addColorStop(0.5, '#d02a4e');
      gradientRed.addColorStop(1, '#cb1c42');

      gradientGold.addColorStop(0, '#eebf5f');
      gradientGold.addColorStop(0.5, '#eece5e');
      gradientGold.addColorStop(1, '#eaba58');

      const segments = [];
      const prizes = [...this.patternData[0].prizes];
      const prizeLocation = [...this.patternData[0].prizes_location];
      prizeLocation.forEach((location, index) => {
        if (index === 0) {
          segments[0] = {
            text: 'Bonus',
            fillStyle: '#25b66f',
            textFillStyle: '#efefef',
            prize: 'Bonus',
            type: 'Bonus',
            amount: this.patternData[0].bonus_current,
          };
        } else {
          const prize = prizes.filter(e => e.id === location)[0];
          segments[index] = {
            text: ['Ticket'].includes(prize.type) ? prize.amount.toString() : ['Code'].includes(prize.type) ? 'Code' : prize.name,
            fillStyle: index % 2 !== 0 ? gradientRed : gradientGold,
            textFillStyle: '#efefef',
            prize: prize.name,
            type: prize.type,
            amount: prize.amount,
          };
        }
      });
      return segments;
    },
    handleSpinStop(segment, segmentIndex) {
      this.isShowWinningDialog = true;
      this.prizeWinning = segment.prize.toString();
      if (['Ticket', 'Bonus'].includes(segment.type.toString())) {
        if (this.storeGame.ticket_remaining_by_session === null) {
          this.storeGame.ticket_remaining_by_session = [];
        }
        this.storeGame.ticket_remaining_by_session.push(segment.amount);
      }
      this.checkTicketRemaining();
      this.bonusReset(segment);
      this.hideWinningDialog(segment);
      if (this.storeGame.coin < 1) {
        this.showDemoDialog();
      }
    },
    handleCloseDemoDialog() {
      this.countDown();
    },
    handleCloseWinDialog() {
      this.isWheelReady = true;
      if (this.storeGame.coin > 0) {
        this.countDown();
      }
      this.enableSendTicket();
    },
    handleAddCoin() {
      StoreResource.update(1, { coin: 1 }).then(() => {
        if (this.patternData[0].bonus_auto_increment) {
          this.bonusIncrement(() => {
            this.getStoreGame().then(() => {
              setTimeout(() => {
                this.isShowDemoDialog = false;
              }, 200);
            });
          });
        } else {
          this.getStoreGame().then(() => {
            setTimeout(() => {
              this.isShowDemoDialog = false;
            }, 200);
          });
        }
      });
    },
    onResize() {
      this.windowHeight = window.innerHeight;
      this.windowWidth = window.innerWidth;
    },
    startPin() {
      if (this.storeGame.coin > 0 && this.isWheelReady) {
        clearInterval(this.timeAutoplay);
        this.timePlay = 0;
        this.isWheelReady = false;
        StoreResource.update(1, { coin: -1 }).then(() => {
          this.getStoreGame().then(() => {
            this.$refs.wheel.handleStart();
          });
        });
      }
    },
    bonusIncrement(callback) {
      PatternResource.update(1, { bonusIncrement: 1 }).then(() => {
        this.getBonusData(() => {
          callback();
        });
      });
    },
    bonusReset(segment, callback) {
      if (segment.prize.toString() === 'Bonus') {
        PatternResource.update(1, { bonusReset: 1 }).then(() => {
          this.getBonusData(() => {
            if (callback) {
              callback();
            }
          });
        });
      }
    },
    hideWinningDialog(segment) {
      const time = segment.name !== 'Bonus' ? this.config.time_show_congratulation_short : this.config.time_show_congratulation_long;
      setTimeout(() => {
        this.isShowWinningDialog = false;
      }, time);
    },
    showDemoDialog() {
      setTimeout(() => {
        this.isShowDemoDialog = true;
      }, this.config.time_back_demo);
    },
    mills2second(mills) {
      return mills / 1000;
    },
    countDown() {
      this.timePlay = this.mills2second(this.config.time_auto_play);
      if (this.timeAutoplay) {
        clearInterval(this.timeAutoplay);
      }
      this.timeAutoplay = setInterval(() => {
        this.timePlay -= 1;
        if (this.timePlay <= 0) {
          this.startPin();
          clearInterval(this.timeAutoplay);
        }
      }, 1000);
    },
    checkTicketRemaining() {
      if (this.storeGame.ticket_remaining_by_session.length > 0 && (this.ticketReturn <= 0 || !this.ticketReturn)) {
        this.ticketReturn = this.storeGame.ticket_remaining_by_session[this.storeGame.ticket_remaining_by_session.length - 1];
        delete this.storeGame.ticket_remaining_by_session[this.storeGame.ticket_remaining_by_session.length - 1];
        this.storeGame.ticket_remaining_by_session = this.storeGame.ticket_remaining_by_session.filter(e => e != null);
      }
      this.enableSendTicket();
    },
    enableSendTicket() {
      if (this.ticketReturn > 0) {
        this.$socket.emit('activeTicket', Number(this.config.io_ticket_output));
        if (this.timeReturnTicket) {
          clearInterval(this.timeReturnTicket);
        }
        this.timeReturnTicket = setTimeout(() => {
          this.$socket.emit('activeTicket', Number(!this.config.io_ticket_output));
          console.log('Error Ticket');
        }, 500);
      }
    },
    disableSendTicket() {
      this.$socket.emit('activeTicket', Number(!this.config.io_ticket_output));
      if (this.timeReturnTicket) {
        clearInterval(this.timeReturnTicket);
      }
    },
  },
};
</script>

<style scoped lang="scss">

.lucky-wheel--background--bottom {
  width: 100vw;
  height: 100vh;
  display: block;
  position: absolute;
  background: #065887;
  clip-path: polygon(0vw 50vh, 0vw 100vh, 100vw 100vh, 100vw 60vh, 70vw 60vh, 55vw 50vh, 50vw 50vh);
}

</style>

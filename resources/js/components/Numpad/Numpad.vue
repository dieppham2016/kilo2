<template>
  <transition>
    <div class="numpad--wrapper">
      <div class="numpad--input__wrapper">
        <span class="numpad--input__display">{{ value }}</span>
      </div>
      <div class="numpad--button__wrapper">
        <div class="numpad--row">
          <button class="numpad--button" @click="handleAddNumber(1)">1</button>
          <button class="numpad--button" @click="handleAddNumber(2)">2</button>
          <button class="numpad--button" @click="handleAddNumber(3)">3</button>
        </div>
        <div class="numpad--row">
          <button class="numpad--button" @click="handleAddNumber(4)">4</button>
          <button class="numpad--button" @click="handleAddNumber(5)">5</button>
          <button class="numpad--button" @click="handleAddNumber(6)">6</button>
        </div>
        <div class="numpad--row">
          <button class="numpad--button" @click="handleAddNumber(7)">7</button>
          <button class="numpad--button" @click="handleAddNumber(8)">8</button>
          <button class="numpad--button" @click="handleAddNumber(9)">9</button>
        </div>
        <div class="numpad--row">
          <button class="numpad--button" @click="handleClearNumber"><i class="el-icon-close" /></button>
          <button class="numpad--button" @click="handleAddNumber(0)">0</button>
          <button class="numpad--button" @click="handleConfirm"><i class="el-icon-check" /></button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'Numpad',
  props: {
    onConfirm: {
      type: Function,
      default: () => {},
    },
    onError: {
      type: Function,
      default: () => {},
    },
    maxLength: {
      type: Number,
      default: 4,
    },
    minLength: {
      type: Number,
      default: 4,
    },
    autoConfirm: Boolean,
  },
  data() {
    return {
      value: '',
    };
  },
  watch: {
    value(val) {
      if (this.autoConfirm) {
        setInterval(() => {
          this.handleConfirm();
        }, 500);
      }
    },
  },
  methods: {
    handleAddNumber(number) {
      if (this.value.length < this.maxLength) {
        this.value += number;
      }
    },
    handleClearNumber() {
      if (this.value.length > 0) {
        this.value = this.value.slice(0, -1);
      }
    },
    handleConfirm() {
      if (this.value.length >= this.minLength) {
        this.onConfirm(this.value);
        this.value = '';
      } else {
        this.onError(this.value.length, this.minLength);
      }
    },
  },
};
</script>

<style lang="scss" scoped>
  .numpad--wrapper {
    width: 40rem;
    margin-top: 2rem;

    .numpad--input__wrapper {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      .numpad--input__display{
        display: flex;
        flex-grow: 1;
        justify-content: flex-end;
        padding: 1rem 3rem;
        font-size: 2rem;
        border: 1px solid #dddddd;
        user-select: none;
        min-height: 5rem;
        line-height: 1.4;
      }
    }

    .numpad--button__wrapper {
      width: 100%;
      height: 100%;

      .numpad--row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;

        .numpad--button {
          display: flex;
          padding: 1rem 3rem;
          font-size: 3rem;
          flex-grow: 1;
          justify-content: center;
          align-self: center;
          min-height: 5rem;
          height: 7rem;
          max-height: 10rem;
          width: 5rem;
          user-select: none;
        }
      }

    }
  }

</style>

<template>
  <el-row
    class="wheel-background"
    :style="{height: windowHeight + 'px', background: 'transparent'}"
    type="flex"
    justify="center"
    align="middle"
  >

    <!--    <div style="width: 505; height: 50px" class="wheel-container" />-->
    <div :style="{height: 100 * scaleFactor + 'px', width: 100 * scaleFactor + 'px'}" class="wheel-container" />
    <canvas
      id="canvas"
      :width="wheelWidth"
      :height="wheelHeight"
      style="background: transparent"
    >
      Canvas not supported, use another browser.
    </canvas>
  </el-row>
</template>

<script>

import * as Winwheel from 'vue-winwheel/Winwheel';
import GameResource from '@/api/game-resource';

const GamePlayResource = new GameResource('wheel/game');

export default {
  name: 'Wheel',
  components: {},
  props: {
    segments: {
      type: Array,
      default: () => [],
    },
    maxSegment: {
      type: Number,
      default: 12,
    },
    isLoading: {
      type: Boolean,
      default: false,
    },

  },
  data() {
    return {
      wheel: null,
      wheelSpinning: false,
      isLoadingSegment: false,
      windowHeight: window.innerHeight,
      windowWidth: window.innerWidth,
      scaleFactor: 1,
      audioSpin: null,
      audioVictory: null,
    };
  },
  computed: {
    wheelWidth() {
      // return window._.min([this.windowWidth, this.windowHeight]) / 2;
      return this.windowWidth / 1.3;
    },
    wheelHeight() {
      return this.windowHeight / 1.3;
    },
  },
  watch: {
    segments(val, oldValue) {
      if (window._.difference(val, oldValue).length > 0) {
        this.drawWheel();
      }
    },
    wheelWidth(val, old) {
      if (val !== old) {
        this.$nextTick(() => this.drawWheel());
      }
    },
    wheelHeight(val, old) {
      if (val !== old) {
        this.$nextTick(() => this.drawWheel());
      }
    },
  },
  created() {
  },
  mounted() {
    this.$nextTick(() => {
      if (this.segments !== null) {
        this.drawWheel();
      }
      window.addEventListener('resize', this.onResize);
    });
    this.audioSpin = new Audio('/sounds/wheel.mp3');
    this.audioVictory = new Audio('/sounds/victory.mp3');
  },
  methods: {
    drawWheel() {
      this.wheel = new Winwheel.Winwheel({
        numSegments: this.maxSegment, // Number of segments
        // innerRadius: this.wheelHeight / 8,
        outerRadius: this.wheelHeight / 2 - 20,
        innerRadius: 50,
        pointerAngle: 0,
        textFontSize: this.wheelHeight / 14, // Font size.
        segments: this.segments,
        animation: // Definition of the animation
          {
            type: 'spinToStop',
            duration: 3,
            spins: 8,
            callbackAfter: this.drawTriangle,
            callbackFinished: this.handleSpinStop,
          },
      });
      this.getScaleFactor();
      this.wheel.rotationAngle = 0; // Re-set the wheel angle to 0 degrees.
      this.wheel.draw(); // Call draw to render changes to the wheel.
      this.wheelSpinning = false; // Reset to false to power buttons and spin can be clicked again.
      this.drawTriangle();
    },
    drawTriangle() {
      // Get the canvas context the wheel uses.
      const ctx = this.wheel.ctx;

      ctx.strokeStyle = 'navy'; // Set line colour.
      ctx.fillStyle = '#0af'; // Set fill colour.
      ctx.lineWidth = 2;
      ctx.beginPath(); // Begin path.
      ctx.moveTo(this.wheelWidth / 2, -20); // Move to initial position.
      ctx.lineTo(this.wheelWidth / 2 - 40, -20); // Draw lines to make the shape.
      ctx.lineTo(this.wheelWidth / 2, 25);
      ctx.lineTo(this.wheelWidth / 2 + 40, -20);
      ctx.stroke(); // Complete the path by stroking (draw lines).
      ctx.fill(); // Then fill.
    },
    handleSpinStop(indicatedSegment) {
      this.audioSpin.pause();
      this.audioSpin.currentTime = 0;
      this.audioVictory.pause();
      this.audioVictory.currentTime = 0;
      this.audioVictory.play();
      this.$emit('spinStop', indicatedSegment, this.wheel.getIndicatedSegmentNumber());
      this.wheel.rotationAngle %= 360;
    },
    async getDegrees(callback) {
      GamePlayResource.degrees().then((response) => {
        if (callback) {
          callback(response);
        }
      });
    },
    handleStart() {
      this.playSound();
      this.getDegrees((response) => {
        this.wheel.animation.stopAngle = parseInt(response.data);
        this.wheel.startAnimation();
      });
    },
    playSound() {
      this.audioSpin.pause();
      this.audioSpin.currentTime = 0;
      this.audioVictory.pause();
      this.audioVictory.currentTime = 0;
      this.audioSpin.play();
    },
    wheelFinish() {
    },
    wheelPlaySound() {
    },
    onResize() {
      this.windowHeight = window.innerHeight;
      this.windowWidth = window.innerWidth;
    },
    getScaleFactor() {
      let margin = 40;
      const canvas = document.getElementById('canvas');

      // If a value has been specified for this then update the margin to it.
      if (typeof (canvas.dataset.responsivemargin) !== 'undefined') {
        margin = canvas.dataset.responsivemargin;
      }

      // Get the current width and also optional min and max width properties.
      let width = this.windowWidth - margin;
      const minWidth = canvas.dataset.responsiveminwidth;

      // Adjust the width as it cannot be larger than the original size of the wheel and we don't want
      // the canvas and wheel inside it to be too small so check the min width.
      if (width < minWidth) {
        width = minWidth;
      } else if (width > canvas.dataset.responsivemaxwidth) {
        width = canvas.dataset.responsivemaxwidth;
      }

      // Work out the percent the new width is smaller than the original width.
      const percent = (width / canvas.dataset._originalCanvasWidth);

      // OK so now we have the percent, set the scaleFactor of the wheel to this.
      this.scaleFactor = percent;
    },
  },
};
</script>

<style scoped>
.wheel-container {
  position: absolute;
  background-image: url("../../../assets/logo.jpg");
  background-repeat: no-repeat;
  background-position: center;
  background-size: cover;
}

</style>

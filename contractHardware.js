#!/usr/bin/env nodejs
const ip = require('ip');
const express = require('express');
const app = express();

console.log(ip.address());
/**
 * ----------------------------------------------
 * PORT của web socket
 * ----------------------------------------------
 * */
const webSocketPort = 2910;

/**
 * ----------------------------------------------
 * PORT server socket lắng nghe sự kiện
 * ----------------------------------------------
 * */
const serverPort = 2911;

/**
 * ----------------------------------------------
 * Nghe sự kiện từ client
 * ----------------------------------------------
 * */
const server = require('http').Server(app).listen(webSocketPort, function() {
  console.log('WebSocket listening on port %d', webSocketPort);
});

/**
 * ----------------------------------------------
 * Định nghĩa Socket IO
 * ----------------------------------------------
 * */

const io = require('socket.io')(server);

/**
 * ----------------------------------------------
 * Node Server
 * ----------------------------------------------
 * */
app.listen(serverPort, function() {
  console.log('Node server is listening on port %d', serverPort);
});

/**
 * ----------------------------------------------
 * Định nghĩa thư viện GPIO Raspberry Pi
 * ----------------------------------------------
 * */
const Gpio = require('onoff').Gpio;

/**
 * ------------------------------
 * Mức trạng thái nhận tín hiệu
 * ------------------------------
 * */
const buttonStateActive = 0;

/**
 * ------------------------------
 * Thời gian chống nhiễu
 * ------------------------------
 * */
const inputDebounceTime = 100; // ms

/**
 * ----------------------------------------------
 * Trạng thái tín hiệu input lần cuối nhận được
 * -----------------------------------------------
 * */
let lastStateButtonStart = 1;
let lastStateCoinSignal = 1;
let lastStateSignalTicket = 1;
let lastStateSignalClearTicket = 1;

/**
 * ------------------------------
 * Định nghĩa nút start
 * ------------------------------
 * */

const buttonStart = new Gpio(17, 'in', 'both');
const signalCoin = new Gpio(11, 'in', 'both');
const signalTicket = new Gpio(5, 'in', 'both');
const signalClearTicket = new Gpio(9, 'in', 'both');

const activeTicket = new Gpio(27, 'out');
activeTicket.writeSync(1);
/**
 * ------------------------------
 * Khi đã kết nối socket
 * ------------------------------
 * */
io.sockets.on('connection', (socket) => {
  console.log('client connect: %s, IP: %s:%s', socket.id, socket.request.connection.remoteAddress, socket.request.connection.remotePort);

  /**
   * ------------------------------
   * Khi có tín hiệu từ nút bắt đầu
   * ------------------------------
   * */
  buttonStart.watch(function(err, level) {
    if (err) {
      console.error('There was an error', err);
      return;
    }
    // socket.emit('buttonStartPressed', level);
    if (level !== lastStateButtonStart) {
      signalInput('buttonStartPressed', level);
    }
    lastStateButtonStart = level;
  });

  /**
   * ------------------------------
   * Khi có tín hiệu từ coin
   * ------------------------------
   * */
  signalCoin.watch(function(err, level) {
    if (err) {
      console.error('There was an error', err);
      return;
    }
    if (level !== lastStateCoinSignal) {
      signalInput('coinInput', level);
    }
    lastStateCoinSignal = level;
  });

  /**
   * ------------------------------
   * Khi có tín hiệu từ ổ điểm
   * ------------------------------
   * */
  signalTicket.watch(function(err, level) {
    if (err) {
      console.error('There was an error', err);
      return;
    }
    if (level !== lastStateSignalTicket) {
      signalInput('ticketInput', level);
    }
    lastStateSignalTicket = level;
  });

  /**
   * --------------------------------------------
   * Khi có tín hiệu từ nút reset khi kẹt điểm
   * --------------------------------------------
   * */
  signalClearTicket.watch(function(err, level) {
    if (err) {
      console.error('There was an error', err);
      return;
    }
    if (level !== lastStateSignalClearTicket) {
      signalInput('clearTicket', level);
    }
    lastStateSignalClearTicket = level;
  });

  /**
   * -------------------------------------------------
   * Khi có tín hiệu trả điểm thì kích active ticket
   * -------------------------------------------------
   * */
  socket.on('activeTicket', level => {
    activeTicket.writeSync(level);
  });
});

/**
 * ------------------------------
 * Lọc nhiễu tín hiệu input
 * ------------------------------
 * */
function signalInput(eventName, level) {
  if (level === buttonStateActive) {
    setTimeout(() => {
      if (level === buttonStateActive) {
        console.log('%s: %s', eventName, level);
        io.sockets.emit(eventName, level);
      }
    }, inputDebounceTime);
  }
}

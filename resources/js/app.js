import Vue from 'vue';
import Cookies from 'js-cookie';
import ElementUI from 'element-ui';
import App from './views/App';
import store from './store';
import router from '@/router';
import i18n from './lang'; // Internationalization
import '@/icons'; // icon
import '@/permission'; // permission control
// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';

import * as filters from './filters'; // global filters

window._ = require('lodash');
// window.io = require('socket.io-client');

import VueSocketIO from 'vue-socket.io';

Vue.use(new VueSocketIO({
  // debug: process.env.APP_DEBUG,
  debug: true,
  connection: window.location.hostname + ':2910',
}));

// window.Pusher = require('pusher-js');

// import { HardwareIO } from './hardwareIo';

// window.hardwareIO = new HardwareIO();

// if (typeof io !== 'undefined') {
//   window.Echo = new Echo({
//     broadcaster: 'socket.io',
//     host: window.location.hostname + ':2908',
//   });
// }
// window.Echo = new Echo({
//   broadcaster: 'pusher',
//   key: 'e92cfe11569986fdf257',
//   cluster: 'ap1',
//   forceTLS: false,
//   activityTimeout: 50400000,
// });

Vue.use(ElementUI, {
  size: Cookies.get('size') || 'medium', // set element-ui default size
  i18n: (key, value) => i18n.t(key, value),
});

// register global utility filters.
Object.keys(filters).forEach(key => {
  Vue.filter(key, filters[key]);
});

Vue.config.productionTip = false;

new Vue({
  el: '#app',
  router,
  store,
  i18n,
  render: h => h(App),
});


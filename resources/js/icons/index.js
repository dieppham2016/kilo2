import Vue from 'vue';
// import SvgIcon from '@/components/SvgIcon';
import { library } from '@fortawesome/fontawesome-svg-core';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import { fab } from '@fortawesome/free-brands-svg-icons';
import { FontAwesomeIcon, FontAwesomeLayers, FontAwesomeLayersText }
  from '@fortawesome/vue-fontawesome';

library.add(fas, far, fab);
Vue.component('font-awesome-icon', FontAwesomeIcon);
Vue.component('font-awesome-layers', FontAwesomeLayers);
Vue.component('font-awesome-layers-text', FontAwesomeLayersText);
// register globally
// Vue.components('svg-icon', SvgIcon);

// const requireAll = requireContext => requireContext.keys().map(requireContext);
// const req = require.context('./svg', false, /\.svg$/);
// requireAll(req);

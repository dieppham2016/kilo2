import Layout from '@/layout';
import WheelPrize from '@/views/wheel-prize/WheelPrize';
import WheelPattern from '@/views/wheel-pattern/WheelPattern';
import WheelChangeLog from '@/views/wheel-change-log/WheelChangeLog';
import WheelConfig from '@/views/wheel-config/WheelConfig';

const gameRoutes = {
  path: '/wheel',
  component: Layout,
  redirect: '/wheel-prize',
  name: 'wheel',
  meta: {
    title: 'lucky-wheel',
    icon: 'dharmachakra',
    permissions: ['view menu game'],
  },
  children: [
    {
      name: 'wheel-prize',
      path: '/wheel-prize',
      component: WheelPrize,
      meta: { title: 'wheel-prize' },
    },
    {
      name: 'wheel-pattern',
      path: '/wheel-pattern',
      component: WheelPattern,
      meta: { title: 'wheel-pattern' },
    },
    {
      name: 'wheel-change-log',
      path: '/wheel-change-log',
      component: WheelChangeLog,
      meta: { title: 'wheel-change-log' },
    },
    {
      name: 'wheel-config',
      path: '/wheel-config',
      component: WheelConfig,
      meta: { title: 'wheel-config' },
    },
  ],
};
export default gameRoutes;

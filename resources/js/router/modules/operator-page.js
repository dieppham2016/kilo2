import Layout from '@/layout/index';

const operatorPagesRoutes = {
  path: '/operator-pages',
  component: Layout,
  redirect: '/operator-pages/queue-config',
  name: 'operatorPages',
  alwaysShow: true,
  meta: {
    title: 'operatorPages',
    icon: 'newspaper',
    permissions: ['view menu administrator'],
  },
  children: [
    {
      path: 'queue-manager',
      component: () => import('@/views/queue-manager/QueueManager'),
      name: 'queue-manager',
      meta: { title: 'queueManager', icon: 'tools', permissions: ['manage live-pages queue'] },
    },
    {
      path: 'queue',
      component: () => import('@/views/queue/Queue'),
      name: 'queue',
      meta: { title: 'queue', icon: 'eye', permissions: ['manage live-pages queue show'] },
    },
  ],
};

export default operatorPagesRoutes;

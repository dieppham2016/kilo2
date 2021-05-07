import Layout from '@/layout/index';

const queueRoutes = {
  path: '/queue-config',
  component: Layout,
  redirect: 'content-manager',
  children: [
    {
      path: 'content-manager',
      component: () => import('@/views/queue-config/QueueConfig'),
      name: 'contentManager',
      meta: { title: 'contentManager', icon: 'poll-h', noCache: false, permissions: ['view menu administrator'] },
    },
  ],
};

export default queueRoutes;

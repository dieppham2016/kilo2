import Layout from '@/layout';

const mediaRoutes = {
  path: '/media',
  component: Layout,
  redirect: 'image',
  name: 'media',
  meta: {
    title: 'media',
    icon: 'photo-video',
    permissions: ['view menu administrator'],
  },
  children: [
    {
      path: 'image',
      component: () => import('@/views/media/Image'),
      name: 'image',
      meta: { title: 'image', icon: 'image', noCache: false },
    },
    // {
    //   path: 'video',
    //   component: () => import('@/views/media/Video'),
    //   name: 'video',
    //   meta: { title: 'video', icon: 'video', noCache: false },
    // },
    // {
    //   path: 'audio',
    //   component: () => import('@/views/media/Audio'),
    //   name: 'audio',
    //   meta: { title: 'audio', icon: 'music', noCache: false },
    // },
  ],
};

export default mediaRoutes;

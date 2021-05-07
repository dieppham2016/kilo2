import Layout from '@/layout';

const billRoutes = {
  path: '/hoa-don',
  component: Layout,
  redirect: '/hoa-don/danh-sach',
  name: 'bill',
  alwaysShow: true,
  meta: {
    title: 'bill',
    icon: 'receipt',
    permissions: ['view menu administrator'],
  },
  children: [
    {
      path: 'danh-sach',
      component: () => import('@/views/bill/List'),
      name: 'list_bill',
      meta: { title: 'list_bill', icon: 'th-list', permissions: ['manage bill'] },
    },
    {
      path: 'trang-thai',
      component: () => import('@/views/statuses/List'),
      name: 'list_status',
      meta: { title: 'list_status', icon: 'th-list', permissions: ['manage status'] },
    },
  ],
};

export default billRoutes;

Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'featured-products',
      path: '/featured-products',
      component: require('./components/Tool'),
    },
  ])
})

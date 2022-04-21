export default {
  // Admin
  1: [
    {
      label: 'Home',
      items: [{ label: 'Dashboard', icon: 'pi pi-home', to: '/dashboards', component: 'home/Index' }],
    },
    {
      label: 'Master',
      items: [{ label: 'User', icon: 'pi pi-user', to: '/users', component: 'user/Index' }],
    },
  ],

  // Supervisor
  2: [
    {
      label: 'Home',
      items: [{ label: 'Dashboard', icon: 'pi pi-home', to: '/dashboards', component: 'home/Index' }],
    },
  ],

  // Operator
  3: [
    {
      label: 'Home',
      items: [{ label: 'Dashboard', icon: 'pi pi-home', to: '/dashboards', component: 'home/Index' }],
    },
  ],
}

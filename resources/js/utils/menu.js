export default {
  // Admin
  1: [
    {
      label: 'Home',
      items: [{ label: 'Dashboard', icon: 'pi pi-home', to: '/dashboards', component: 'home/Index' }],
    },
    {
      label: 'Menu',
      items: [
        {
          label: 'Submenu 1',
          icon: 'pi pi-bookmark',
          items: [{ label: 'Submenu 1.1', icon: 'pi pi-bookmark' }],
        },
      ],
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

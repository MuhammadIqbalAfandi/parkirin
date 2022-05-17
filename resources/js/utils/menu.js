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
    {
      label: 'Menu',
      items: [
        { label: 'Pengeluaran', icon: 'pi pi-wallet', to: '/expenses', component: 'expense/Index' },
        {
          label: 'Laporan',
          icon: 'pi pi-book',
          items: [{ label: 'Mutasi', icon: 'pi pi-circle', to: '/reports/mutations', component: 'mutation/Report' }],
        },
      ],
    },
    {
      label: 'Master',
      items: [
        { label: 'Jenis Kendaraan', icon: 'pi pi-car', to: '/type-vehicles', component: 'typevehicle/Index' },
        { label: 'Jenis Member', icon: 'pi pi-id-card', to: '/type-members', component: 'typemember/Index' },
      ],
    },
    {
      label: 'Pengaturan',
      items: [{ label: 'Tarif Parkir', icon: 'pi pi-car', to: '/parking-fees/create', component: 'parkingfee/Create' }],
    },
  ],

  // Operator
  3: [
    {
      label: 'Home',
      items: [{ label: 'Dashboard', icon: 'pi pi-home', to: '/dashboards', component: 'home/Index' }],
    },
    {
      label: 'Menu',
      items: [
        { label: 'Pengeluaran', icon: 'pi pi-wallet', to: '/expenses', component: 'expense/Index' },
        { label: 'Member', icon: 'pi pi-id-card', to: '/members', component: 'member/Index' },
        { label: 'Top Up', icon: 'pi pi-car', to: '/top-ups', component: 'topup/Index' },
      ],
    },
  ],
}

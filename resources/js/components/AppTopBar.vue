<script setup>
import { Link } from '@inertiajs/inertia-vue3'

defineEmits(['menu-toggle'])
</script>

<template>
  <div class="layout-topbar">
    <Link href="/" class="layout-topbar-logo">
      <img alt="Logo" src="/images/logo.svg" class="md:mr-3" />
      <span>Parkirin</span>
    </Link>

    <button class="p-link layout-menu-button layout-topbar-button" @click="$emit('menu-toggle', $event)">
      <i class="pi pi-bars"></i>
    </button>
    <button
      class="p-link layout-topbar-menu-button layout-topbar-button"
      v-styleclass="{
        selector: '@next',
        enterClass: 'hidden',
        enterActiveClass: 'scalein',
        leaveToClass: 'hidden',
        leaveActiveClass: 'fadeout',
        hideOnOutsideClick: true,
      }"
    >
      <i class="pi pi-ellipsis-v"></i>
    </button>
    <ul class="layout-topbar-menu hidden lg:flex origin-top">
      <li class="align-self-center">
        <span class="hidden lg:inline">{{ $page.props.auth.user.name }}</span>
      </li>
      <li>
        <Link class="p-link layout-topbar-button">
          <i class="pi pi-user"></i>
          <span>Profil Saya</span>
        </Link>
      </li>
      <li>
        <Link :href="route('logout')" as="button" method="post" class="p-link layout-topbar-button">
          <i class="pi pi-sign-out"></i>
          <span>Sign Out</span>
        </Link>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import AppButtonLink from '@/components/AppButtonLink.vue'
import AuthLayout from '@/layouts/AuthLayout.vue'
</script>

<template>
  <Head title="Verifikasi Email" />

  <AuthLayout>
    <template #header> Email verifikasi telah terkirim ke email Anda yang Anda berikan saat pendaftaran </template>

    <AppButtonLink
      label="Kirim ulang email verifikasi"
      class="w-full p-3 text-xl mb-3"
      method="post"
      :href="route('verification.send')"
    />

    <AppButtonLink label="Sign Out" class="w-full p-3 text-xl p-button-text" method="post" :href="route('logout')" />
  </AuthLayout>
</template>

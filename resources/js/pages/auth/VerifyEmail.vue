<script setup>
import AppButton from '@/components/AppButton.vue'
import AuthSessionSuccess from '@/components/AuthSessionSuccess.vue'
</script>

<template>
  <div class="surface-0 flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
    <div class="grid flex-column align-items-center p-2 lg:p-0" style="min-width: 80%">
      <div class="col-12 xl:col-6">
        <AuthSessionSuccess />
      </div>

      <div
        class="col-12 xl:col-6"
        style="
          border-radius: 56px;
          padding: 0.3rem;
          background: linear-gradient(180deg, var(--primary-color), rgba(33, 150, 243, 0) 30%);
        "
      >
        <div
          class="h-full w-full m-0 py-7 px-4"
          style="border-radius: 53px; background: linear-gradient(180deg, var(--surface-50) 38.9%, var(--surface-0))"
        >
          <div class="text-center mb-5 text-600 font-medium">
            <span> Email verifikasi telah terkirim ke email Anda yang Anda berikan saat pendaftaran </span>
          </div>

          <div class="flex align-items-center justify-content-between mb-2">
            <AppButton
              label="Kirim ulang email verifikasi"
              class="w-full p-3 text-xl"
              method="post"
              :href="route('verification.send')"
            />
          </div>

          <AppButton label="Sign Out" class="w-full p-3 text-xl p-button-text" method="post" :href="route('logout')" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import AppLayout from '@/layouts/AppLayout.vue'

defineProps({
  expense: Object,
})
</script>

<template>
  <Head title="Detail Pengeluaran" />
  <AppLayout>
    <h1 class="text-2xl font-bold">Detail Pengeluaran</h1>

    <div class="grid px-2">
      <div class="col-auto mr-7">
        <h2>
          <span class="text-base"> <i class="pi pi-user" /> User</span>

          <br />

          <span class="text-lg">{{ expense.user.name }}</span>
        </h2>
      </div>

      <div class="col-auto mr-7">
        <h2>
          <span class="text-base"> <i class="pi pi-mobile" /> No HP</span>

          <br />

          <span class="text-lg">{{ expense.user.phone }}</span>
        </h2>
      </div>

      <div class="col-auto">
        <h2>
          <span class="text-base"> <i class="pi pi-at" /> Email</span>

          <br />

          <span class="text-lg">{{ expense.user.email }}</span>
        </h2>
      </div>
    </div>

    <div class="grid">
      <div class="col-12">
        <h2 class="mb-4">
          <span class="text-base">
            <i class="pi pi-wallet" />
            Pengeluaran
          </span>

          <br />

          <span class="text-lg">{{ expense.amount }}</span>
        </h2>
      </div>
    </div>

    <div class="grid">
      <div class="col-12 md:col-8">
        <Card>
          <template #content>
            <h5>Keterangan</h5>
            <p v-html="expense.description"></p>
          </template>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import DashboardLayout from '@/layouts/DashboardLayout.vue'

defineProps({
  expense: Object,
})
</script>

<template>
  <Head title="Detail Pengeluaran" />
  <DashboardLayout>
    <div class="grid">
      <div class="col-12 md:col-8">
        <Card>
          <template #title> Detail Pengeluaran </template>
          <template #content>
            <div class="grid">
              <div class="col-12">
                <h2>
                  <span class="text-base">
                    <i class="pi pi-user" />
                    Petugas Parkir
                  </span>

                  <br />

                  <span class="text-lg">{{ expense.user.name }}</span>
                </h2>
              </div>

              <div v-if="expense.user.phone" class="col-12">
                <h2>
                  <span class="text-base">
                    <i class="pi pi-phone" /> Nomor HP
                  </span>

                  <br />

                  <span class="text-lg">{{ expense.user.phone }}</span>
                </h2>
              </div>

              <div class="col-12">
                <h2>
                  <span class="text-base"> <i class="pi pi-at" /> Email </span>

                  <br />

                  <span class="text-lg">{{ expense.user.email }}</span>
                </h2>
              </div>

              <div class="col-12">
                <h2>
                  <span class="text-base">
                    <i class="pi pi-wallet" />
                    Pengeluaran
                  </span>

                  <br />

                  <span class="text-lg">{{ expense.amount }}</span>
                </h2>
              </div>

              <div class="col-12">
                <h5>Keterangan</h5>
                <p v-html="expense.description"></p>
              </div>
            </div>
          </template>
        </Card>
      </div>
    </div>
  </DashboardLayout>
</template>

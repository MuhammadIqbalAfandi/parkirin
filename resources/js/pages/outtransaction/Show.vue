<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import TableHeader from './tableHeader'
import DashboardLayout from '@/layouts/DashboardLayout.vue'

defineProps({
  user: String,
  outTransaction: Object,
})
</script>

<template>
  <Head title="Detail Top Up" />

  <DashboardLayout>
    <DataTable
      responsive-layout="scroll"
      column-resize-mode="expand"
      :value="outTransaction"
      :row-hover="true"
      :striped-rows="true"
    >
      <template #header>
        <h1 class="text-2xl font-bold">Detail Transaksi Keluar</h1>

        <div class="grid px-2">
          <div class="col-auto mr-7">
            <h2>
              <span class="text-base">
                <i class="pi pi-user" />
                Petugas Parkir
              </span>

              <br />

              <span class="text-lg">{{ user }}</span>
            </h2>
          </div>
        </div>
      </template>

      <Column
        v-for="value in TableHeader"
        :field="value.field"
        :header="value.header"
        :key="value.field"
      />
    </DataTable>
  </DashboardLayout>
</template>

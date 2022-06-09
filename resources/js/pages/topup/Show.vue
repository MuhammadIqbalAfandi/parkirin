<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import { topUpTable } from './tableHeader'
import AppPagination from '@/components/AppPagination.vue'
import DashboardLayout from '@/layouts/DashboardLayout.vue'

defineProps({
  member: Object,
  topUp: Object,
})
</script>

<template>
  <Head title="Detail Top Up" />

  <DashboardLayout>
    <DataTable
      responsive-layout="scroll"
      column-resize-mode="expand"
      :value="topUp.data"
      :row-hover="true"
      :striped-rows="true"
    >
      <template #header>
        <h1 class="text-2xl font-bold">Detail Top Up</h1>

        <div class="grid px-2">
          <div class="col-auto mr-7">
            <h2>
              <span class="text-base"> <i class="pi pi-user" /> Nama</span>

              <br />

              <span class="text-lg">{{ member.name }}</span>
            </h2>
          </div>
        </div>
      </template>

      <Column v-for="value in topUpTable" :field="value.field" :header="value.header" :key="value.field" />
    </DataTable>

    <AppPagination :links="topUp.links" />
  </DashboardLayout>
</template>

<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import tableHeader from './tableHeader'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import AppPagination from '@/components/AppPagination.vue'
import AppButtonLink from '@/components/AppButtonLink.vue'

defineProps({
  typeVehicles: Object,
})
</script>

<template>
  <Head title="Jenis Kendaraan" />

  <DashboardLayout>
    <DataTable
      responsive-layout="scroll"
      column-resize-mode="expand"
      :value="typeVehicles.data"
      :row-hover="true"
      :striped-rows="true"
    >
      <template #header>
        <h1>Jenis Kendaraan</h1>

        <div class="grid">
          <div class="col-12 flex flex-column md:flex-row justify-content-end">
            <AppButtonLink
              label="Tambah Jenis Kendaraan"
              class="p-button-outlined"
              icon="pi pi-pencil"
              :href="route('type-vehicles.create')"
            />
          </div>
        </div>
      </template>

      <Column
        v-for="value in tableHeader"
        :field="value.field"
        :header="value.header"
        :key="value.field"
      />

      <Column>
        <template #body="{ data }">
          <AppButtonLink
            v-if="!data.isUsed"
            icon="pi pi-pencil"
            class="p-button-text p-button-icon-only p-button-rounded p-button-text"
            :href="route('type-vehicles.edit', data.id)"
          />
        </template>
      </Column>
    </DataTable>

    <AppPagination :links="typeVehicles.links" />
  </DashboardLayout>
</template>

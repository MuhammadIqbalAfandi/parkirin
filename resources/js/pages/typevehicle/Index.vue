<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import AppPagination from '@/components/AppPagination.vue'
import AppButton from '@/components/AppButton.vue'

import TableHeader from './TableHeader'

defineProps({
  typeVehicles: Object,
})
</script>

<template>
  <Head title="Jenis Kendaraan" />

  <AppLayout>
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
            <AppButton
              label="Tambah Jenis Kendaraan"
              class="p-button-outlined"
              icon="pi pi-pencil"
              :href="route('type-vehicles.create')"
            />
          </div>
        </div>
      </template>

      <Column
        v-for="tableHeader in TableHeader"
        :field="tableHeader.field"
        :header="tableHeader.header"
        :key="tableHeader.field"
      />

      <Column>
        <template #body="{ data }">
          <AppButton
            v-if="!data.isUsed"
            icon="pi pi-angle-double-right"
            class="p-button-text p-button-icon-only p-button-rounded p-button-text"
            :href="route('type-vehicles.edit', data.id)"
          />
        </template>
      </Column>
    </DataTable>

    <AppPagination :links="typeVehicles.links" />
  </AppLayout>
</template>

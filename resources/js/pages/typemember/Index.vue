<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import AppButton from '@/components/AppButton.vue'
import AppLayout from '@/layouts/AppLayout.vue'

import { IndexTable } from './TableHeader'

defineProps({
  typeMembers: Object,
})
</script>

<template>
  <Head title="Daftar Jenis Member" />

  <AppLayout>
    <DataTable
      responsive-layout="scroll"
      column-resize-mode="expand"
      :value="typeMembers"
      :row-hover="true"
      :striped-rows="true"
    >
      <template #header>
        <h1>Jenis Member</h1>

        <div class="grid">
          <div class="col-12">
            <div class="flex flex-column md:flex-row justify-content-end">
              <AppButton
                label="Tambah Jenis Member"
                class="p-button-outlined"
                icon="pi pi-pencil"
                :href="route('type-members.create')"
              />
            </div>
          </div>
        </div>
      </template>

      <Column
        v-for="indexTable in IndexTable"
        :field="indexTable.field"
        :header="indexTable.header"
        :key="indexTable.field"
      />

      <Column>
        <template #body="{ data }">
          <AppButton
            icon="pi pi-pencil"
            class="p-button-text p-button-icon-only p-button-rounded p-button-text"
            :href="route('type-members.edit', data.id)"
          />
        </template>
      </Column>
    </DataTable>
  </AppLayout>
</template>

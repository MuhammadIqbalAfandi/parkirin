<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import { indexTable } from './tableHeader'
import AppPagination from '@/components/AppPagination.vue'
import AppButtonLink from '@/components/AppButtonLink.vue'
import DashboardLayout from '@/layouts/DashboardLayout.vue'

defineProps({
  typeMember: Object,
})
</script>

<template>
  <Head title="Daftar Jenis Member" />

  <DashboardLayout>
    <DataTable
      responsive-layout="scroll"
      column-resize-mode="expand"
      :value="typeMember.data"
      :row-hover="true"
      :striped-rows="true"
    >
      <template #header>
        <h1>Jenis Member</h1>

        <div class="grid">
          <div class="col-12">
            <div class="flex flex-column md:flex-row justify-content-end">
              <AppButtonLink
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
        v-for="value in indexTable"
        :field="value.field"
        :header="value.header"
        :key="value.field"
      />

      <Column>
        <template #body="{ data }">
          <AppButtonLink
            icon="pi pi-pencil"
            class="p-button-text p-button-icon-only p-button-rounded p-button-text"
            v-tooltip.bottom="'Detail Jenis Member'"
            :href="route('type-members.edit', data.id)"
          />
        </template>
      </Column>
    </DataTable>

    <AppPagination :links="typeMember.links" />
  </DashboardLayout>
</template>

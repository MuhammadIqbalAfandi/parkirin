<script setup>
import { watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head, useForm } from '@inertiajs/inertia-vue3'
import throttle from 'lodash/throttle'
import pickBy from 'lodash/pickBy'
import AppLayout from '@/layouts/AppLayout.vue'
import AppButton from '@/components/AppButton.vue'
import AppPagination from '@/components/AppPagination.vue'

import TableHeader from './TableHeader'

const props = defineProps({
  users: Object,
  filters: Object,
})

const filterForm = useForm({
  search: props.filters.search,
})

watch(
  filterForm,
  throttle(() => {
    Inertia.get('/users', pickBy({ search: filterForm.search }), { preserveState: true })
  }, 300)
)
</script>

<template>
  <Head title="Daftar User" />

  <AppLayout>
    <DataTable
      responsiveLayout="scroll"
      columnResizeMode="expand"
      :value="users.data"
      :rowHover="true"
      :stripedRows="true"
    >
      <template #header>
        <h1>User</h1>

        <div class="grid">
          <div class="col-12 md:col-8">
            <div class="flex align-items-center">
              <InputText class="w-full md:w-27rem" placeholder="cari..." v-model="filterForm.search" />
            </div>
          </div>

          <div class="col-12 md:col-4 flex flex-column md:flex-row justify-content-end">
            <AppButton
              label="Tambah User"
              icon="pi pi-pencil"
              class="p-button-outlined"
              :href="route('users.create')"
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
            icon="pi pi-angle-double-right"
            class="p-button-icon-only p-button-rounded p-button-text"
            :href="route('users.edit', data.id)"
          />
        </template>
      </Column>
    </DataTable>

    <AppPagination :links="users.links" />
  </AppLayout>
</template>

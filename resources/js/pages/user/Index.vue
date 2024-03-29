<script setup>
import { watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head } from '@inertiajs/inertia-vue3'
import { pickBy } from 'lodash'
import tableHeader from './tableHeader'
import { useSearchText } from '@/components/useSearchText'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import AppButtonLink from '@/components/AppButtonLink.vue'
import AppPagination from '@/components/AppPagination.vue'

const props = defineProps({
  users: Object,
  initialSearch: String,
})

const { search } = useSearchText(props)

watch(search, () => {
  Inertia.get('/users', pickBy({ search: search.value }), {
    preserveState: true,
  })
})
</script>

<template>
  <Head title="Daftar User" />

  <DashboardLayout>
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
              <InputText
                class="w-full md:w-27rem"
                placeholder="cari, contoh: tina, tina@xx.com"
                v-model="search"
              />
            </div>
          </div>

          <div
            class="col-12 md:col-4 flex flex-column md:flex-row justify-content-end"
          >
            <AppButtonLink
              label="Tambah User"
              icon="pi pi-pencil"
              class="p-button-outlined"
              :href="route('users.create')"
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
            icon="pi pi-pencil"
            class="p-button-icon-only p-button-rounded p-button-text"
            v-tooltip.bottom="'Ubah User'"
            :href="route('users.edit', data.id)"
          />
        </template>
      </Column>
    </DataTable>

    <AppPagination :links="users.links" />
  </DashboardLayout>
</template>

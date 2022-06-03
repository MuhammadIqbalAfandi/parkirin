<script setup>
import { watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head } from '@inertiajs/inertia-vue3'
import { pickBy } from 'lodash'
import { indexTable } from './tableHeader'
import { useSearchText } from '@/components/useSearchText'
import AppButtonLink from '@/components/AppButtonLink.vue'
import AppPagination from '@/components/AppPagination.vue'
import DashboardLayout from '@/layouts/DashboardLayout.vue'

const props = defineProps({
  members: Object,
  initialSearch: String,
})

const { search } = useSearchText(props.initialSearch)

watch(search, () => {
  Inertia.get('/members', pickBy({ search: search.value }), { preserveState: true })
})
</script>

<template>
  <Head title="Daftar Member" />

  <DashboardLayout>
    <DataTable
      responsive-layout="scroll"
      column-resize-mode="expand"
      :value="members.data"
      :row-hover="true"
      :striped-rows="true"
    >
      <template #header>
        <h1>Daftar Member</h1>

        <div class="grid">
          <div class="col-12 md:col-8">
            <div class="flex align-items-center">
              <InputText class="w-full md:w-27rem" placeholder="cari, contoh: 08xx, tina" v-model="search" />
            </div>
          </div>

          <div class="col-12 md:col-4 flex flex-column md:flex-row justify-content-end">
            <AppButtonLink
              label="Tambah Member"
              class="p-button-outlined"
              icon="pi pi-pencil"
              :href="route('members.create')"
            />
          </div>
        </div>
      </template>

      <Column v-for="value in indexTable" :field="value.field" :header="value.header" :key="value.field" />

      <Column>
        <template #body="{ data }">
          <AppButtonLink
            icon="pi pi-pencil"
            class="p-button-text p-button-icon-only p-button-rounded p-button-text"
            :href="route('members.edit', data.id)"
          />
        </template>
      </Column>
    </DataTable>

    <AppPagination :links="members.links" />
  </DashboardLayout>
</template>

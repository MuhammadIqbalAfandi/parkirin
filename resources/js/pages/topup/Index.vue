<script setup>
import { Inertia } from '@inertiajs/inertia'
import { watch } from 'vue'
import { Head } from '@inertiajs/inertia-vue3'
import { pickBy } from 'lodash'
import { indexTable } from './tableHeader'
import { useSearchText } from '@/components/useSearchText'
import { useDateRangeFilter } from '@/components/useDateRangeFilter'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import AppPagination from '@/components/AppPagination.vue'
import AppButtonLink from '@/components/AppButtonLink.vue'

const props = defineProps({
  topUp: Object,
  initialSearch: String,
  initialDateRange: Array,
})

const { search } = useSearchText(props.initialSearch)
const { dates, startDate, endDate } = useDateRangeFilter(props.initialDateRange)

watch([dates, search], () => {
  Inertia.get(
    '/top-ups',
    pickBy({
      startDate: startDate.value,
      endDate: endDate.value,
      search: search.value,
    }),
    {
      preserveState: true,
    }
  )
})

const filterReset = () => {
  Inertia.get('/top-ups')
}
</script>

<template>
  <Head title="Top Up" />

  <DashboardLayout>
    <DataTable
      responsive-layout="scroll"
      column-resize-mode="expand"
      :value="topUp.data"
      :row-hover="true"
      :striped-rows="true"
    >
      <template #header>
        <h1>Top Up</h1>

        <div class="grid">
          <div class="col-12 md:col-8">
            <div class="grid">
              <div class="col-12 md:col-3">
                <InputText class="w-full" placeholder="cari, contoh: 08xx, tina" v-model="search" />
              </div>
              <div class="col-12 md:col-3">
                <Calendar
                  touch-u-i
                  class="w-full"
                  v-model="dates"
                  selection-mode="range"
                  placeholder="filter waktu..."
                  date-format="dd/mm/yy"
                  :manual-input="false"
                />
              </div>
              <div class="col-auto mt-2 ml-2">
                <Button label="reset" class="p-button-link" @click="filterReset" />
              </div>
            </div>
          </div>

          <div class="col-12 md:col-4 flex flex-column md:flex-row justify-content-end">
            <AppButtonLink
              label="Top Up"
              class="p-button-outlined"
              icon="pi pi-pencil"
              :href="route('top-ups.create')"
            />
          </div>
        </div>
      </template>

      <Column v-for="value in indexTable" :field="value.field" :header="value.header" :key="value.field" />

      <Column>
        <template #body="{ data }">
          <AppButtonLink
            icon="pi pi-eye"
            class="p-button-text p-button-icon-only p-button-rounded p-button-text"
            :href="route('top-ups.show', data.id)"
          />
        </template>
      </Column>
    </DataTable>

    <AppPagination :links="topUp.links" />
  </DashboardLayout>
</template>

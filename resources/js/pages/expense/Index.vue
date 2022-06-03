<script setup>
import { Inertia } from '@inertiajs/inertia'
import { watch } from 'vue'
import { Head } from '@inertiajs/inertia-vue3'
import { pickBy } from 'lodash'
import tableHeader from './tableHeader'
import { useDateRangeFilter } from '@/components/useDateRangeFilter'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import AppPagination from '@/components/AppPagination.vue'
import AppButtonLink from '@/components/AppButtonLink.vue'

const props = defineProps({
  expenses: Object,
  initialDateRage: Array,
})

const { dates, startDate, endDate } = useDateRangeFilter(props.initialDateRage)

watch(dates, () => {
  Inertia.get(
    '/expenses',
    pickBy({
      startDate: startDate.value,
      endDate: endDate.value,
    }),
    {
      preserveState: true,
    }
  )
})

const filterReset = () => {
  Inertia.get('/expenses')
}
</script>

<template>
  <Head title="Pengeluaran" />

  <DashboardLayout>
    <DataTable
      responsive-layout="scroll"
      column-resize-mode="expand"
      :value="expenses.data"
      :row-hover="true"
      :striped-rows="true"
    >
      <template #header>
        <h1>Pengeluaran</h1>

        <div class="grid">
          <div class="col-12 md:col-8">
            <div class="grid">
              <div class="col-12 md:col-4">
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
              label="Tambah Pengeluaran"
              class="p-button-outlined"
              icon="pi pi-pencil"
              :href="route('expenses.create')"
            />
          </div>
        </div>
      </template>

      <Column v-for="value in tableHeader" :field="value.field" :header="value.header" :key="value.field" />

      <Column>
        <template #body="{ data }">
          <AppButtonLink
            icon="pi pi-eye"
            class="p-button-text p-button-icon-only p-button-rounded p-button-text"
            :href="route('expenses.show', data.id)"
          />
        </template>
      </Column>
    </DataTable>

    <AppPagination :links="expenses.links" />
  </DashboardLayout>
</template>

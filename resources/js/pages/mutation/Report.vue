<script setup>
import { watch, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head } from '@inertiajs/inertia-vue3'
import { pickBy } from 'lodash'
import tableHeader from './tableHeader'
import { useDateRangeFilter } from '@/components/useDateRangeFilter'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import AppPagination from '@/components/AppPagination.vue'
import AppButtonLink from '@/components/AppButtonLink.vue'

const props = defineProps({
  mutations: {
    type: Object,
    default: {
      details: {
        data: [],
        links: [],
        total: 0,
      },
    },
  },
  initialDateRage: Array,
})

const { dates, startDate, endDate } = useDateRangeFilter(props.initialDateRage)

watch(dates, () => {
  Inertia.reload({
    data: pickBy({
      startDate: startDate.value,
      endDate: endDate.value,
    }),
    only: ['mutations'],
  })

  const params = window.location.search
  exportExcelLink.value = `/reports/mutations/export/excel${params}`
})

const filterReset = () => {
  Inertia.get('/reports/mutations')
}

const linkReference = (data) => {
  if (data.topUpId) {
    return route('top-ups.show', data.topUpId)
  } else if (data.expenseId) {
    return route('expenses.show', data.expenseId)
  } else {
    return route('out-transactions.show', data.outTransactionId)
  }
}

const exportExcelLink = ref('/reports/mutations/export/excel')
</script>

<template>
  <DashboardLayout>
    <Head title="Laporan Mutasi" />

    <DataTable
      responsive-layout="scroll"
      column-resize-mode="expand"
      :value="mutations.details.data"
      :row-hover="true"
      :striped-rows="true"
    >
      <template #header>
        <h1>Laporan Mutasi</h1>

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
              v-if="mutations.details.total"
              label="Export excel"
              class-button="p-button-outlined md:w-16rem"
              icon="pi pi-file-excel"
              :inertia-link="false"
              :href="exportExcelLink"
            />
          </div>
        </div>

        <div v-if="mutations.totalAmount" class="grid mt-5 ml-1">
          <div class="col-auto mr-7">
            <h2>
              <span class="text-base"> <i class="pi pi-wallet" /> Pendapatan</span>

              <br />

              <span class="text-xl font-bold">{{ mutations.totalIncome }}</span>
            </h2>
          </div>
          <div class="col-auto mr-7">
            <h2>
              <span class="text-base"> <i class="pi pi-wallet" /> Pengeluaran</span>

              <br />

              <span class="text-xl font-bold">{{ mutations.totalExpense }}</span>
            </h2>
          </div>
          <div class="col-auto">
            <h2>
              <span class="text-base"> <i class="pi pi-wallet" /> Total </span>

              <br />

              <span class="text-xl font-bold">{{ mutations.totalAmount }}</span>
            </h2>
          </div>
        </div>
      </template>

      <Column v-for="value in tableHeader" :field="value.field" :header="value.header" :key="value.field" />

      <Column>
        <template #body="{ data }">
          <AppButtonLink
            icon="pi pi-link"
            class="p-button-text p-button-icon-only p-button-rounded p-button-text"
            :href="linkReference(data)"
          />
        </template>
      </Column>
    </DataTable>

    <AppPagination :links="mutations.details.links" />
  </DashboardLayout>
</template>

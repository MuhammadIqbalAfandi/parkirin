<script setup>
import { watch, onMounted, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head, useForm } from '@inertiajs/inertia-vue3'
import dayjs from 'dayjs'
import { pickBy } from 'lodash'
import AppLayout from '@/layouts/AppLayout.vue'
import AppPagination from '@/components/AppPagination.vue'
import AppButton from '@/components/AppButton.vue'

import TableHeader from './TableHeader'

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
  filters: Object,
})

const filterForm = useForm({
  dates: null,
  startDate: props.filters.startDate,
  endDate: props.filters.endDate,
})

onMounted(() => {
  if (props.filters.startDate || props.filters.endDate) {
    if (props.filters.endDate) {
      filterForm.dates = [new Date(props.filters.startDate), new Date(props.filters.endDate)]
    } else {
      filterForm.dates = [new Date(props.filters.startDate), null]
    }
  }
})

watch(filterForm, () => {
  if (filterForm.dates) {
    if (filterForm.dates[1]) {
      filterForm.startDate = dayjs(filterForm.dates[0]).format('YYYY-MM-DD')
      filterForm.endDate = dayjs(filterForm.dates[1]).format('YYYY-MM-DD')
    } else {
      filterForm.startDate = dayjs(filterForm.dates[0]).format('YYYY-MM-DD')
      filterForm.endDate = null
    }
  } else {
    filterForm.endDate = null
    filterForm.startDate = null
  }

  Inertia.reload({
    data: pickBy({
      startDate: filterForm.startDate,
      endDate: filterForm.endDate,
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
  if (data.topupId) {
    return route('top-ups.show', data.topupId)
  } else if (data.expenseId) {
    return route('expenses.show', data.expenseId)
  } else {
    console.info('under construction')
    // return route('expenses.show', data.expenseId)
  }
}

const exportExcelLink = ref('/reports/mutations/export/excel')
</script>

<template>
  <AppLayout>
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
                  class="w-full"
                  v-model="filterForm.dates"
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
            <AppButton
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

      <Column
        v-for="tableHeader in TableHeader"
        :field="tableHeader.field"
        :header="tableHeader.header"
        :key="tableHeader.field"
      />

      <Column>
        <template #body="{ data }">
          <AppButton
            icon="pi pi-link"
            class="p-button-text p-button-icon-only p-button-rounded p-button-text"
            :href="linkReference(data)"
          />
        </template>
      </Column>
    </DataTable>

    <AppPagination :links="mutations.details.links" />
  </AppLayout>
</template>

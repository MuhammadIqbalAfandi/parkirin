<script setup>
import { Head } from '@inertiajs/inertia-vue3'
import AppPagination from '@/components/AppPagination.vue'
import AppLayout from '@/layouts/AppLayout.vue'

import { TopUpsTable } from './TableHeader'

defineProps({
  member: Object,
  topUp: Object,
})
</script>

<template>
  <Head title="Detail Top Up" />

  <AppLayout>
    <DataTable
      responsive-layout="scroll"
      column-resize-mode="expand"
      :value="topUp.data"
      :row-hover="true"
      :striped-rows="true"
    >
      <template #header>
        <h1 class="text-2xl font-bold">Detail Top Up</h1>

        <div class="grid px-2">
          <div class="col-auto mr-7">
            <h2>
              <span class="text-base"> <i class="pi pi-user" /> Nama</span>

              <br />

              <span class="text-lg">{{ member.name }}</span>
            </h2>
          </div>

          <div class="col-auto">
            <h2>
              <span class="text-base"> <i class="pi pi-phone" /> Nomor HP </span>

              <br />

              <span class="text-lg">{{ member.phone }}</span>
            </h2>
          </div>
        </div>
      </template>

      <Column
        v-for="topUpsTable in TopUpsTable"
        :field="topUpsTable.field"
        :header="topUpsTable.header"
        :key="topUpsTable.field"
      />
    </DataTable>

    <AppPagination :links="topUp.links" />
  </AppLayout>
</template>

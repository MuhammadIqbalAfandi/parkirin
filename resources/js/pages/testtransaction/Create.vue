<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import TableHeader from './TableHeader'
import AppPagination from '@/components/AppPagination.vue'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import { onMounted } from 'vue'

defineProps({
  entryTransactions: Object,
  typeVehicles: Object,
})

const form = useForm({})

const submit = () => {
  form.post(route('test-transactions.store'))
}
</script>
<template>
  <DashboardLayout>
    <div class="grid">
      <div class="col-12 md:col-6">
        <Card>
          <template #title> <h1>Entry</h1> </template>
          <template #content>
            <DataTable
              responsiveLayout="scroll"
              columnResizeMode="expand"
              :value="entryTransactions.data"
              :rowHover="true"
              :stripedRows="true"
            >
              <template #header>
                <span style="color: var(--primary-color)">
                  Maksimal ditampilkan :
                </span>
                {{ entryTransactions.per_page }}
                <br />
                <span style="color: var(--primary-color)">Ditampilkan :</span>
                {{ Object.keys(entryTransactions.data).length }}
              </template>

              <Column
                v-for="value in TableHeader"
                :field="value.field"
                :header="value.header"
                :key="value.field"
              />
            </DataTable>

            <AppPagination :links="entryTransactions.links" />
          </template>
          <template #footer>
            <div class="flex flex-column md:flex-row justify-content-end">
              <Button
                label="Car Entry"
                icon="pi pi-check"
                class="p-button-outlined"
                :disabled="form.processing"
                @click="submit"
              />
            </div>
          </template>
        </Card>
      </div>
    </div>
  </DashboardLayout>
</template>

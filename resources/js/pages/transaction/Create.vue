<script setup>
import { watch } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { useForm, Head } from '@inertiajs/inertia-vue3'
import { useConfirm } from 'primevue/useconfirm'
import FormValidationError from '@/utils/formValidationError'
import { useFormErrorReset } from '@/components/useFormErrorReset'
import AppInputText from '@/components/AppInputText.vue'
import AppDropdown from '@/components/AppDropdown.vue'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import IndexTable from './TableHeader'

const props = defineProps({
  outTransactions: Object,
  typeVehicles: Object,
  detailOutTransaction: Object,
})

const form = useForm({
  plat_number: null,
  entry_transaction_id: null,
  type_vehicle_id: null,
})

useFormErrorReset(form)

watch(
  () => form.plat_number,
  () => {
    Inertia.reload({
      only: ['typeVehicles'],
      data: { plat_number: form.plat_number },
    })
  }
)

const confirm = useConfirm()

const submitValidation = () => {
  form.clearErrors()

  const fieldError = []

  if (!form.plat_number) {
    fieldError.push('plat_number')
  }

  if (!form.entry_transaction_id) {
    fieldError.push('entry_transaction_id')
  }

  if (!form.type_vehicle_id) {
    fieldError.push('type_vehicle_id')
  }

  if (fieldError.length > 0) {
    throw new FormValidationError('Nilai tidak boleh kosong', fieldError)
  }
}

const submit = () => {
  try {
    submitValidation()

    Inertia.reload({
      only: ['detailOutTransaction'],
      data: { transaction_number: form.entry_transaction_id },
    })

    confirm.require({
      message: `
      Lama parkir ${props.detailOutTransaction.totalTimeParking},
      biaya yang dikenakan ${props.detailOutTransaction.totalPriceParking}
    `,
      header: 'Tagihan',
      acceptLabel: 'Bayar dan simpan',
      rejectLabel: 'Batalkan',
      accept: () => {
        form.post(route('transactions.store'), {
          onSuccess: () => form.reset(),
        })
      },
      reject: () => {
        confirm.close()
      },
    })
  } catch ($e) {
    if ($e.field) {
      $e.field.forEach((field) => form.setError(field, $e.message))
    }
  }
}
</script>
<template>
  <Head title="Transaksi Keluar" />

  <DashboardLayout>
    <ConfirmDialog></ConfirmDialog>

    <div class="grid">
      <div class="col-12">
        <Card>
          <template #title> Tambah Transaksi Keluar </template>
          <template #content>
            <div class="grid">
              <div class="col-12 md:col-6">
                <AppInputText
                  v-model="form.plat_number"
                  label="Plat Kendaraan"
                  placeholder="plat kendaraan"
                  :error="form.errors.plat_number"
                />
              </div>
              <div class="col-12 md:col-6">
                <AppInputText
                  v-model="form.entry_transaction_id"
                  label="Nomor Transaksi"
                  placeholder="nomor transaksi"
                  :error="form.errors.entry_transaction_id"
                />
              </div>
              <div class="col-12 md:col-6">
                <AppDropdown
                  v-model="form.type_vehicle_id"
                  label="Jenis Kendaraan"
                  placeholder="jenis kendaraan"
                  :options="typeVehicles"
                  :error="form.errors.type_vehicle_id"
                />
              </div>
            </div>
          </template>
          <template #footer>
            <div class="flex flex-column md:flex-row justify-content-end">
              <Button
                label="Simpan"
                icon="pi pi-check"
                class="p-button-outlined"
                :disabled="form.processing"
                @click="submit"
              />
            </div>
          </template>
        </Card>
      </div>
      <div class="col-12">
        <Card>
          <template #title> Kendaraan Keluar </template>
          <template #content>
            <DataTable
              responsiveLayout="scroll"
              columnResizeMode="expand"
              :value="outTransactions"
              :rowHover="true"
              :stripedRows="true"
            >
              <template #header>
                <span style="color: var(--red-600)">
                  Maksimal ditampilkan :
                </span>
                10
                <br />
                <span style="color: var(--red-600)">Ditampilkan :</span>
                {{ Object.keys(outTransactions).length }}
              </template>

              <Column
                v-for="value in IndexTable"
                :field="value.field"
                :header="value.header"
                :key="value.field"
              />
            </DataTable>
          </template>
        </Card>
      </div>
    </div>
  </DashboardLayout>
</template>

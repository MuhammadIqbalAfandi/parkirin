<script setup>
import { Inertia } from '@inertiajs/inertia'
import { Head, useForm } from '@inertiajs/inertia-vue3'
import { useConfirm } from 'primevue/useconfirm'
import { vehicleTable } from './tableHeader'
import { useVehicle } from './useVehicle'
import { useFormErrorReset } from '@/components/useFormErrorReset'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import AppInputText from '@/components/AppInputText.vue'
import AppDropdown from '@/components/AppDropdown.vue'
import AppInputNumber from '@/components/AppInputNumber.vue'
import AppTextArea from '@/components/AppTextArea.vue'

const props = defineProps({
  typeMember: Object,
  typeVehicles: Object,
  initialVehicles: Object,
})

const deleteConfirm = useConfirm()

const onDeleteTypeMember = () => {
  deleteConfirm.require({
    message: `Yakin akan menghapus (${props.typeMember.type}) ?`,
    header: 'Hapus Jenis Member',
    acceptLabel: 'Hapus',
    rejectLabel: 'Batalkan',
    accept: () => {
      Inertia.delete(route('type-members.destroy', props.typeMember.id))
    },
    reject: () => {
      deleteConfirm.close()
    },
  })
}

const form = useForm({
  type: props.typeMember.type,
  description: props.typeMember.description,
  price: props.typeMember.price,
  type_vehicle_id: null,
  max_vehicle: null,
})

useFormErrorReset(form)

const { listVehicle, vehicleOnAdd, vehicleOnDelete } = useVehicle(props, form)

const onSubmit = () => {
  form
    .transform((data) => ({
      type: data.type,
      description: data.description,
      price: data.price,
      maxVehicles: listVehicle,
    }))
    .put(route('type-members.update', props.typeMember.id))
}
</script>

<template>
  <Head title="Ubah Jenis Member" />

  <DashboardLayout>
    <ConfirmDialog></ConfirmDialog>

    <div class="grid">
      <div class="col-12 md:col-8">
        <Card>
          <template #title> Ubah Jenis Member </template>
          <template #content>
            <div class="grid">
              <div class="col-12 md:col-6">
                <AppInputText
                  v-model="form.type"
                  label="Jenis Member"
                  placeholder="jenis member"
                  :error="form.errors.type"
                />
              </div>

              <div class="col-12 md:col-6">
                <AppInputNumber
                  v-model="form.price"
                  label="Tarif Member"
                  placeholder="contoh: 100000"
                  :error="form.errors.price"
                />
              </div>

              <div class="col-12">
                <AppTextArea
                  v-model="form.description"
                  label="Keterangan"
                  placeholder="tulis keterangan disini"
                  :error="form.errors.description"
                />
              </div>
            </div>
          </template>
        </Card>
      </div>
    </div>

    <div class="grid">
      <div class="col-12 md:col-8">
        <Card>
          <template #content>
            <div class="grid">
              <div class="col-12 md:col-6">
                <AppDropdown
                  label="Jenis Kendaraan"
                  placeholder="jenis kendaraan"
                  v-model="form.type_vehicle_id"
                  :options="typeVehicles"
                  :error="form.errors.type_vehicle_id"
                />
              </div>

              <div class="col-12 md:col-6">
                <AppInputNumber
                  v-model="form.max_vehicle"
                  label="Maksimal Kendaraan"
                  placeholder="contoh: 1"
                  :error="form.errors.max_vehicle"
                />
              </div>

              <div class="col-12">
                <div
                  class="flex flex-column md:flex-row md:justify-content-end"
                >
                  <Button
                    label="Tambah"
                    icon="pi pi-car"
                    class="p-button-outlined"
                    @click="vehicleOnAdd"
                  />
                </div>
              </div>

              <div class="col-12 md-col-6">
                <DataTable
                  striped-rows
                  row-hover
                  responsive-layout="scroll"
                  column-resize-mode="expand"
                  edit-mode="cell"
                  :value="listVehicle"
                >
                  <Column
                    v-for="value in vehicleTable"
                    :field="value.field"
                    :header="value.header"
                    :key="value.field"
                  />
                  <Column>
                    <template #body="{ index }">
                      <div class="flex justify-content-end">
                        <Button
                          icon="pi pi-trash"
                          class="p-button-rounded p-button-text"
                          @click="vehicleOnDelete(index)"
                        />
                      </div>
                    </template>
                  </Column>
                </DataTable>
              </div>
            </div>
          </template>
          <template #footer>
            <div class="grid">
              <div class="col-12 md:col-6">
                <div
                  v-if="!typeMember.availableToMember"
                  class="flex flex-column md:flex-row justify-content-center md:justify-content-start"
                >
                  <Button
                    v-if="!typeMember.isUsed"
                    label="Hapus"
                    icon="pi pi-trash"
                    class="p-button-outlined p-button-danger"
                    @click="onDeleteTypeMember"
                  />
                </div>
              </div>

              <div class="col-12 md:col-6">
                <div
                  class="flex flex-column md:flex-row md:justify-content-end"
                >
                  <Button
                    label="Simpan"
                    icon="pi pi-check"
                    class="p-button-outlined"
                    :disabled="form.processing || listVehicle.length === 0"
                    @click="onSubmit"
                  />
                </div>
              </div>
            </div>
          </template>
        </Card>
      </div>
    </div>
  </DashboardLayout>
</template>

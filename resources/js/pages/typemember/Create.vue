<script setup>
import { Head, useForm } from '@inertiajs/inertia-vue3'
import { vehicleTable } from './tableHeader'
import { useVehicle } from './useVehicle'
import { useFormErrorReset } from '@/components/useFormErrorReset'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import AppInputText from '@/components/AppInputText.vue'
import AppDropdown from '@/components/AppDropdown.vue'
import AppInputNumber from '@/components/AppInputNumber.vue'
import AppTextArea from '@/components/AppTextArea.vue'

const props = defineProps({
  typeVehicles: Object,
})

const form = useForm({
  type: null,
  description: null,
  price: null,
  type_vehicle_id: null,
  max_vehicle: null,
})

useFormErrorReset(form)

const { listVehicle, vehicleClear, vehicleOnAdd, vehicleOnDelete } = useVehicle(
  props,
  form
)

const onSubmit = () => {
  form
    .transform((data) => ({
      type: data.type,
      description: data.description,
      price: data.price,
      maxVehicles: listVehicle,
    }))
    .post(route('type-members.store'), {
      onSuccess: () => {
        vehicleClear()

        form.reset()
      },
    })
}
</script>

<template>
  <Head title="Tambah Jenis Member" />

  <DashboardLayout>
    <div class="grid">
      <div class="col-12 md:col-8">
        <Card>
          <template #title> Tambah Jenis Member </template>
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
            <div class="flex flex-column md:flex-row md:justify-content-end">
              <Button
                label="Simpan"
                icon="pi pi-check"
                class="p-button-outlined"
                :disabled="form.processing || listVehicle.length === 0"
                @click="onSubmit"
              />
            </div>
          </template>
        </Card>
      </div>
    </div>
  </DashboardLayout>
</template>

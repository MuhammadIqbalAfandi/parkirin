<script setup>
import { computed, watch, reactive, onMounted, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head, useForm, usePage } from '@inertiajs/inertia-vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import AppInputText from '@/components/AppInputText.vue'
import AppDropdown from '@/components/AppDropdown.vue'
import AppDialog from '@/components/AppDialog.vue'
import AppInputNumber from '@/components/AppInputNumber.vue'
import AppTextArea from '@/components/AppTextArea.vue'

import { Vehicle } from './TableHeader'

const props = defineProps({
  typeMember: Object,
  typeVehicles: Object,
  maxVehicles: Object,
})

const errors = computed(() => usePage().props.value.errors)

watch(errors, () => {
  form.clearErrors()
})

const visibleDialog = ref(false)

const confirmDialog = () => {
  visibleDialog.value = true
}

const onAgree = () => Inertia.delete(route('type-members.destroy', props.typeMember.id))

const onCancel = () => (visibleDialog.value = false)

const listVehicle = reactive([])

onMounted(() => {
  props.maxVehicles.forEach((val) => listVehicle.push(val))
})

const vehicleOnDelete = (index) => {
  listVehicle.splice(index, 1)
}

const vehicleOnAdd = () => {
  form.clearErrors('type_vehicle_id', 'max_vehicle')

  if (!form.type_vehicle_id) {
    form.setError('type_vehicle_id', 'Tidak boleh kosong')

    return
  }

  if (!form.max_vehicle) {
    form.setError('max_vehicle', 'Tidak boleh kosong')

    return
  }

  const listVehicleExist = listVehicle.filter((val) => val.typeVehicleId === form.type_vehicle_id)
  if (listVehicleExist.length) {
    form.setError('type_vehicle_id', 'Jenis Kendaraan sudah ada')

    return
  }

  const typeVehicle = props.typeVehicles.filter((val) => val.value === form.type_vehicle_id)[0]
  listVehicle.push({
    max: form.max_vehicle,
    type: typeVehicle.label,
    typeVehicleId: typeVehicle.value,
  })

  form.reset('type_vehicle_id', 'max_vehicle')
}

const form = useForm({
  type: props.typeMember.type,
  description: props.typeMember.description,
  price: props.typeMember.price,
  type_vehicle_id: null,
  max_vehicle: null,
})

const submit = () => {
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

  <AppLayout>
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
                  placeholder="tarif member"
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
                  placeholder="maksimal kendaraan"
                  :error="form.errors.max_vehicle"
                />
              </div>

              <div class="col-12">
                <div class="flex flex-column md:flex-row md:justify-content-end">
                  <Button label="Tambah" icon="pi pi-car" class="p-button-outlined" @click="vehicleOnAdd" />
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
                    v-for="vehicle in Vehicle"
                    :field="vehicle.field"
                    :header="vehicle.header"
                    :key="vehicle.field"
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
                  <AppDialog
                    message="Yakin akan menghapus data ini?"
                    v-model:visible="visibleDialog"
                    @agree="onAgree"
                    @cancel="onCancel"
                  />

                  <Button
                    v-if="!typeMember.isUsed"
                    label="Hapus"
                    icon="pi pi-trash"
                    class="p-button-outlined p-button-danger"
                    @click="confirmDialog"
                  />
                </div>
              </div>

              <div class="col-12 md:col-6">
                <div class="flex flex-column md:flex-row md:justify-content-end">
                  <Button
                    label="Simpan"
                    icon="pi pi-check"
                    class="p-button-outlined"
                    :disabled="form.processing || listVehicle.length === 0"
                    @click="submit"
                  />
                </div>
              </div>
            </div>
          </template>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>

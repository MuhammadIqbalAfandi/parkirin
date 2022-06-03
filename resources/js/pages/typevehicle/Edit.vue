<script setup>
import { Inertia } from '@inertiajs/inertia'
import { Head, useForm } from '@inertiajs/inertia-vue3'
import { useConfirm } from 'primevue/useconfirm'
import { useFormErrorReset } from '@/components/useFormErrorReset'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import AppInputText from '@/components/AppInputText.vue'

const props = defineProps({
  typeVehicle: Object,
})

const deleteConfirm = useConfirm()

const onDeleteTypeVehicle = () => {
  deleteConfirm.require({
    message: `Yakin akan menghapus (${props.typeVehicle.type}) ?`,
    header: 'Hapus Jenis Kendaraan',
    acceptLabel: 'Hapus',
    rejectLabel: 'Batalkan',
    accept: () => {
      Inertia.delete(route('type-vehicles.destroy', props.typeVehicle.id))
    },
    reject: () => {
      deleteConfirm.close()
    },
  })
}

const form = useForm({
  type: props.typeVehicle.type,
})

useFormErrorReset(form)

const onSubmit = () => {
  form.put(route('type-vehicles.update', props.typeVehicle.id))
}
</script>

<template>
  <Head title="Ubah Jenis Kendaraan" />

  <DashboardLayout>
    <ConfirmDialog></ConfirmDialog>

    <div class="grid">
      <div class="col-12 md:col-8">
        <Card>
          <template #title>Ubah Jenis Kendaraan </template>
          <template #content>
            <div class="grid">
              <div class="col-12 md:col-6">
                <AppInputText
                  v-model="form.type"
                  label="Jenis Kendaraan"
                  placeholder="contoh: sepeda, mobil, motor ..."
                  :error="form.errors.type"
                />
              </div>
            </div>
          </template>

          <template #footer>
            <div class="grid">
              <div class="col-12 md:col-6 flex flex-column md:flex-row justify-content-center md:justify-content-start">
                <Button
                  label="Hapus"
                  icon="pi pi-trash"
                  class="p-button-outlined p-button-danger"
                  @click="onDeleteTypeVehicle"
                />
              </div>

              <div class="col-12 md:col-6 flex flex-column md:flex-row justify-content-center md:justify-content-end">
                <Button
                  label="Simpan"
                  icon="pi pi-check"
                  class="p-button-outlined"
                  :disabled="form.processing"
                  @click="onSubmit"
                />
              </div>
            </div>
          </template>
        </Card>
      </div>
    </div>
  </DashboardLayout>
</template>

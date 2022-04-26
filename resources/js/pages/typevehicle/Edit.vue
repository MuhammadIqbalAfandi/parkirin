<script setup>
import { computed, watch, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head, useForm, usePage } from '@inertiajs/inertia-vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import AppDialog from '@/components/AppDialog.vue'
import AppInputText from '@/components/AppInputText.vue'

const props = defineProps({
  typeVehicle: Object,
})

const errors = computed(() => usePage().props.value.errors)

watch(errors, () => {
  form.clearErrors()
})

const visibleDialog = ref(false)

const confirmDialog = () => {
  visibleDialog.value = true
}

const onAgree = () => Inertia.delete(route('type-vehicles.destroy', props.typeVehicle.id))

const onCancel = () => (visibleDialog.value = false)

const form = useForm({
  type: props.typeVehicle.type,
})

const submit = () => {
  form.put(route('type-vehicles.update', props.typeVehicle.id))
}
</script>

<template>
  <Head title="Ubah Jenis Kendaraan" />

  <AppLayout>
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
                  placeholder="jenis kendaraan"
                  :error="form.errors.type"
                />
              </div>
            </div>
          </template>

          <template #footer>
            <div class="grid">
              <div class="col-12 md:col-6 flex flex-column md:flex-row justify-content-center md:justify-content-start">
                <AppDialog
                  message="Yakin akan menghapus data ini?"
                  v-model:visible="visibleDialog"
                  @agree="onAgree"
                  @cancel="onCancel"
                />

                <Button
                  label="Hapus"
                  icon="pi pi-trash"
                  class="p-button-outlined p-button-danger"
                  @click="confirmDialog"
                />
              </div>

              <div class="col-12 md:col-6 flex flex-column md:flex-row justify-content-center md:justify-content-end">
                <Button
                  label="Simpan"
                  icon="pi pi-check"
                  class="p-button-outlined"
                  :disabled="form.processing"
                  @click="submit"
                />
              </div>
            </div>
          </template>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/inertia-vue3'
import { useFormErrorReset } from '@/components/useFormErrorReset'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import AppInputText from '@/components/AppInputText.vue'

const form = useForm({
  type: null,
})

useFormErrorReset(form)

const onSubmit = () => {
  form.post(route('type-vehicles.store'), { onSuccess: () => form.reset() })
}
</script>

<template>
  <Head title="Tambah Jenis Kendaraan" />

  <DashboardLayout>
    <div class="grid">
      <div class="col-12 md:col-8">
        <Card>
          <template #title> Tambah Jenis Kendaraan </template>
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
            <div class="flex flex-column md:flex-row justify-content-end">
              <Button
                label="Simpan"
                icon="pi pi-check"
                class="p-button-outlined"
                :disabled="form.processing"
                @click="onSubmit"
              />
            </div>
          </template>
        </Card>
      </div>
    </div>
  </DashboardLayout>
</template>

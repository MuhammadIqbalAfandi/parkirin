<script setup>
import { useForm, Head } from '@inertiajs/inertia-vue3'
import { useFormErrorReset } from '@/components/useFormErrorReset'
import AppInputText from '@/components/AppInputText.vue'
import AppDropdown from '@/components/AppDropdown.vue'
import DashboardLayout from '@/layouts/DashboardLayout.vue'

defineProps({
  roles: Array,
})

const form = useForm({
  name: null,
  phone: null,
  email: null,
  role_id: null,
})

useFormErrorReset(form)

const onSubmit = () => {
  form.post(route('users.store'), { onSuccess: () => form.reset() })
}
</script>

<template>
  <Head title="Tambah User" />

  <DashboardLayout>
    <div class="grid">
      <div class="col-12 lg:col-8">
        <Card>
          <template #title> Tambah User </template>
          <template #content>
            <div class="grid">
              <div class="col-12 md:col-6">
                <AppInputText
                  label="Nama"
                  placeholder="nama"
                  :error="form.errors.name"
                  v-model="form.name"
                />
              </div>

              <div class="col-12 md:col-6">
                <AppInputText
                  label="Nomor HP"
                  placeholder="nomor hp"
                  :error="form.errors.phone"
                  v-model="form.phone"
                />
              </div>

              <div class="col-12 md:col-6">
                <AppInputText
                  label="Email"
                  placeholder="email"
                  :error="form.errors.email"
                  v-model="form.email"
                />
              </div>

              <div class="col-12 md:col-6">
                <AppDropdown
                  label="Hak Akses"
                  placeholder="pilih satu"
                  v-model="form.role_id"
                  :options="roles"
                  :error="form.errors.role_id"
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

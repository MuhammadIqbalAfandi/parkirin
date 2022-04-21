<script setup>
import { ref, watch, computed } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { useForm, Head, usePage } from '@inertiajs/inertia-vue3'
import AppInputText from '@/components/AppInputText.vue'
import AppDropdown from '@/components/AppDropdown.vue'
import AppButton from '@/components/AppButton.vue'
import AppDialog from '@/components/AppDialog.vue'
import AppLayout from '@/layouts/AppLayout.vue'

const props = defineProps({
  user: Object,
  roles: Array,
})

const form = useForm({
  name: props.user.name,
  phone: props.user.phone,
  email: props.user.email,
  role_id: props.user.role_id,
})

const submit = () => {
  form.put(route('users.update', props.user.id))
}

const visibleDialog = ref(false)

const confirmDialog = () => {
  visibleDialog.value = true
}

const onAgree = (id) => Inertia.delete(route('users.destroy', id))

const onCancel = () => (visibleDialog.value = false)

const errors = computed(() => usePage().props.value.errors)

watch(errors, () => {
  form.clearErrors()
})
</script>

<template>
  <Head title="Ubah User" />

  <AppLayout>
    <div class="grid">
      <div class="col-12 lg:col-8">
        <Card>
          <template #title> Edit User </template>
          <template #content>
            <div class="grid">
              <div class="col-12 md:col-6">
                <AppInputText
                  label="Nama"
                  placeholder="nama"
                  :disabled="user.role_id !== 1"
                  :error="form.errors.name"
                  v-model="form.name"
                />
              </div>

              <div class="col-12 md:col-6">
                <AppInputText
                  label="Nomor HP"
                  placeholder="nomor hp"
                  :disabled="user.role_id !== 1"
                  :error="form.errors.phone"
                  v-model="form.phone"
                />
              </div>

              <div class="col-12 md:col-6">
                <AppInputText
                  label="Email"
                  placeholder="email"
                  :disabled="user.role_id !== 1"
                  :error="form.errors.email"
                  v-model="form.email"
                />
              </div>

              <div v-if="user.role_id !== 1" class="col-12 md:col-6">
                <AppDropdown
                  label="Hak Akses"
                  placeholder="Pilih satu"
                  v-model="form.role_id"
                  :options="roles"
                  :error="form.errors.role_id"
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
                  @agree="onAgree(user.id)"
                  @cancel="onCancel"
                />

                <Button
                  v-if="$page.props.auth.user.role_id !== user.role_id"
                  label="Hapus"
                  icon="pi pi-trash"
                  class="p-button-outlined p-button-danger"
                  @click="confirmDialog"
                />
              </div>

              <div class="col-12 md:col-6 flex flex-column md:flex-row justify-content-center md:justify-content-end">
                <AppButton
                  label="Blokir"
                  icon="pi pi-ban"
                  method="delete"
                  class="p-button-outlined p-button-danger md:mr-3 mb-3 md:mb-0"
                  :href="route('users.block', user.id)"
                />

                <Button
                  label="Simpan"
                  class="p-button-outlined"
                  icon="pi pi-check"
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

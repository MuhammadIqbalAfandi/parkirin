<script setup>
import { computed, watch, ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Head, useForm, usePage } from '@inertiajs/inertia-vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import AppDialog from '@/components/AppDialog.vue'
import AppDropdown from '@/components/AppDropdown.vue'
import AppInputText from '@/components/AppInputText.vue'

const props = defineProps({
  member: Object,
  typeMembers: Array,
})

const errors = computed(() => usePage().props.value.errors)

watch(errors, () => {
  form.clearErrors()
})

const visibleDialog = ref(false)

const confirmDialog = () => {
  visibleDialog.value = true
}

const onAgree = () => Inertia.delete(route('users.destroy', props.member.id))

const onCancel = () => (visibleDialog.value = false)

const form = useForm({
  name: props.member.name,
  phone: props.member.phone,
  plat_number: props.member.plat_number,
  type_member_id: props.member.type_member_id,
})

const submit = () => {
  form.put(route('members.update', props.member.id))
}
</script>

<template>
  <Head title="Ubah Member" />

  <AppLayout>
    <div class="grid">
      <div class="col-12 md:col-8">
        <Card>
          <template #title> Ubah Member </template>
          <template #content>
            <div class="grid">
              <div class="col-12 md:col-6">
                <AppInputText v-model="form.name" label="Nama" placeholder="nama" :error="form.errors.name" />
              </div>

              <div class="col-12 md:col-6">
                <AppInputText v-model="form.phone" label="Nomor HP" placeholder="nomor hp" :error="form.errors.phone" />
              </div>

              <div class="col-12 md:col-6">
                <AppInputText
                  v-model="form.plat_number"
                  label="Plat Kendaraan"
                  placeholder="plat kendaraan"
                  :error="form.errors.plat_number"
                />
              </div>

              <div class="col-12 md:col-6">
                <AppDropdown
                  label="Jenis member"
                  placeholder="pilih satu"
                  v-model="form.type_member_id"
                  :options="typeMembers"
                  :error="form.errors.type_member_id"
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

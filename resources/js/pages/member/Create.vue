<script setup>
import { computed, watch } from 'vue'
import { Head, useForm, usePage } from '@inertiajs/inertia-vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import AppDropdown from '@/components/AppDropdown.vue'
import AppInputText from '@/components/AppInputText.vue'

defineProps({
  typeMembers: Array,
})

const errors = computed(() => usePage().props.value.errors)

watch(errors, () => {
  form.clearErrors()
})

const form = useForm({
  name: null,
  phone: null,
  plat_number: null,
  type_member_id: null,
})

const submit = () => {
  form.post(route('members.store'), { onSuccess: () => form.reset() })
}
</script>

<template>
  <Head title="Tambah Member" />

  <AppLayout>
    <div class="grid">
      <div class="col-12 md:col-8">
        <Card>
          <template #title> Tambah Member </template>
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
    </div>
  </AppLayout>
</template>

<script setup>
import { computed, watch } from 'vue'
import { Head, useForm, usePage } from '@inertiajs/inertia-vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import AppInputText from '@/components/AppInputText.vue'
import AppInputNumber from '@/components/AppInputNumber.vue'
import AppTextArea from '@/components/AppTextArea.vue'

const errors = computed(() => usePage().props.value.errors)

watch(errors, () => {
  form.clearErrors()
})

const form = useForm({
  type: null,
  description: null,
  price: null,
})

const submit = () => {
  form.post(route('type-members.store'), { onSuccess: () => form.reset() })
}
</script>

<template>
  <Head title="Tambah Jenis Member" />

  <AppLayout>
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
                  placeholder="tarif member"
                  :error="form.errors.price"
                />
              </div>

              <div class="col-12 sm:col-6">
                <AppTextArea
                  v-model="form.description"
                  label="Keterangan"
                  placeholder="tulis keterangan disini"
                  :error="form.errors.description"
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

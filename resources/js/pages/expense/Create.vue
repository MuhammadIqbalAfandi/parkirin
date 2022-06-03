<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import { useFormErrorReset } from '@/components/useFormErrorReset'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import AppInputNumber from '@/components/AppInputNumber.vue'
import AppEditor from '@/components/AppEditor.vue'

const form = useForm({
  description: null,
  amount: null,
})

useFormErrorReset(form)

const onSubmit = () => {
  form.post(route('expenses.store'), { onSuccess: () => form.reset() })
}
</script>

<template>
  <DashboardLayout>
    <div class="grid">
      <div class="col-12 md:col-8">
        <Card>
          <template #title> Tambah Pengeluaran </template>
          <template #content>
            <AppInputNumber
              v-model="form.amount"
              class="md:w-16rem"
              label="Pengeluaran"
              placeholder="contoh: 100000"
              :error="form.errors.amount"
            />

            <AppEditor
              label="Keterangan"
              v-model="form.description"
              editor-style="height: 320px"
              placeholder="tulis keterangan disini"
              :error="form.errors.description"
            >
              <template #toolbar>
                <span class="q-formats">
                  <button class="ql-bold" v-tooltip.bottom="'Bold'"></button>
                  <button class="ql-italic" v-tooltip.bottom="'Italic'"></button>
                  <button class="ql-underline" v-tooltip.bottom="'Underline'"></button>
                </span>
                <span class="ql-formats">
                  <button class="ql-list" value="ordered" v-tooltip.bottom="'Ordered'"></button>
                  <button class="ql-list" value="bullet" v-tooltip.bottom="'Bullet'"></button>
                </span>
              </template>
            </AppEditor>
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

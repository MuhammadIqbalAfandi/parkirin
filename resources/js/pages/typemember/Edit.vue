<script setup>
import { computed, watch } from 'vue'
import { useForm, usePage } from '@inertiajs/inertia-vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import AppInputText from '@/components/AppInputText.vue'
import AppInputNumber from '@/components/AppInputNumber.vue'

const props = defineProps({
  typeMember: Object,
})

const errors = computed(() => usePage().props.value.errors)

watch(errors, () => {
  form.clearErrors()
})

const form = useForm({
  type: props.typeMember.type,
  price: props.typeMember.price,
})

const submit = () => {
  form.put(route('type-members.update', props.typeMember.id), { onSuccess: () => form.reset() })
}
</script>

<template>
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

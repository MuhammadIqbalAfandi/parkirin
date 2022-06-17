<script setup>
import { Head, useForm } from '@inertiajs/inertia-vue3'
import { useFormErrorReset } from '@/components/useFormErrorReset'
import AppInputText from '@/components/AppInputText.vue'
import AuthLayout from '@/layouts/AuthLayout.vue'

const form = useForm({
  email: '',
})

useFormErrorReset(form)

const onSubmit = () => {
  form.post(route('password.email'))
}
</script>

<template>
  <Head title="Lupa Kata Sandi" />

  <AuthLayout>
    <template #header>
      <span>Lupa Kata Sandi</span> <br />
      <span>Tulis Email anda untuk mereset Kata Sandi.</span>
    </template>

    <AppInputText
      v-model="form.email"
      label="Email"
      placeholder="email"
      :error="form.errors.email"
    />

    <Button
      @click="onSubmit"
      label="Permintaan Kata Sandi baru"
      :disabled="form.processing"
      class="w-full p-3 text-xl"
    />
  </AuthLayout>
</template>

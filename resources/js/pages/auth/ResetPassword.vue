<script setup>
import { Head, useForm } from '@inertiajs/inertia-vue3'
import { useFormErrorReset } from '@/components/useFormErrorReset'
import AppInputText from '@/components/AppInputText.vue'
import AppPassword from '@/components/AppPassword.vue'
import AuthLayout from '@/layouts/AuthLayout.vue'

const props = defineProps({
  token: String,
  email: String,
})

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
})

useFormErrorReset(form)

const onSubmit = () => {
  form.post(route('password.update'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <Head title="Reset Kata Sandi" />

  <AuthLayout>
    <template #header>
      <span>Reset Kata Sandi</span> <br />
      <span>Tulis Kata Sandi baru untuk login</span>
    </template>

    <AppInputText
      v-model="form.email"
      label="Email"
      placeholder="email"
      :error="form.errors.email"
    />

    <AppPassword
      v-model="form.password"
      label="Kata Sandi"
      placeholder="kata sandi"
      :error="form.errors.password"
    />

    <AppPassword
      v-model="form.password_confirmation"
      label="Konfirmasi Kata Sandi"
      placeholder="konfirmasi kata sandi"
      :error="form.errors.password_confirmation"
    />

    <Button
      @click="onSubmit"
      label="Reset Kata Sandi"
      :disabled="form.processing"
      class="w-full p-3 text-xl"
    />
  </AuthLayout>
</template>

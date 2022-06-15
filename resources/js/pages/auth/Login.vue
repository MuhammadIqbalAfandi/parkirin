<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3'
import { useFormErrorReset } from '@/components/useFormErrorReset'
import AppInputText from '@/components/AppInputText.vue'
import AppPassword from '@/components/AppPassword.vue'
import AuthLayout from '@/layouts/AuthLayout.vue'

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

useFormErrorReset(form)

const onSubmit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  })
}
</script>

<template>
  <Head title="Sign In" />

  <AuthLayout>
    <template #header> Masuk untuk melanjutkan </template>

    <AppInputText
      v-model="form.email"
      label="Email"
      placeholder="email"
      :error="form.errors.email"
    />

    <AppPassword
      v-model="form.password"
      label="Password"
      placeholder="password"
      :error="form.errors.password"
    />

    <div class="flex align-items-center justify-content-between mb-5">
      <Link
        :href="route('password.request')"
        class="font-medium no-underline ml-2 text-right cursor-pointer"
        style="color: var(--primary-color)"
      >
        Lupa password?
      </Link>
    </div>

    <Button
      @click="onSubmit"
      label="Masuk"
      :disabled="form.processing"
      class="w-full p-3 text-xl"
    />
  </AuthLayout>
</template>

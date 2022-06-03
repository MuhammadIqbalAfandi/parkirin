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
  <Head title="Lupa Password" />

  <AuthLayout>
    <template #header>
      <span>Lupa Password</span> <br />
      <span>Tulis Email anda untuk mereset Password.</span>
    </template>

    <AppInputText v-model="form.email" label="Email" placeholder="email" :error="form.errors.email" />

    <Button @click="onSubmit" label="Permintaan Password baru" :disabled="form.processing" class="w-full p-3 text-xl" />
  </AuthLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/inertia-vue3'
import AuthSessionSuccess from '@/components/AuthSessionSuccess.vue'
import AuthValidationErrors from '@/components/AuthValidationErrors.vue'

const form = useForm({
  email: '',
})

const submit = () => {
  form.post(route('password.email'))
}
</script>

<template>
  <Head title="Lupa Password" />

  <div class="surface-0 flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
    <div class="grid flex-column align-items-center p-2 lg:p-0" style="min-width: 80%">
      <div class="col-12 xl:col-6">
        <AuthSessionSuccess />
      </div>

      <div class="col-12 xl:col-6">
        <AuthValidationErrors />
      </div>

      <div
        class="col-12 xl:col-6"
        style="
          border-radius: 56px;
          padding: 0.3rem;
          background: linear-gradient(180deg, var(--primary-color), rgba(33, 150, 243, 0) 30%);
        "
      >
        <div
          class="h-full w-full m-0 py-7 px-4"
          style="border-radius: 53px; background: linear-gradient(180deg, var(--surface-50) 38.9%, var(--surface-0))"
        >
          <div class="text-center mb-5 text-600 font-medium">
            <span>Lupa Password</span> <br />
            <span>Tulis Email anda untuk mereset Password.</span>
          </div>

          <div class="w-full md:w-10 mx-auto">
            <label for="email" class="block text-900 text-xl font-medium mb-2">Email</label>
            <InputText
              id="email"
              v-model="form.email"
              type="text"
              class="w-full mb-3"
              placeholder="Email"
              style="padding: 1rem"
            />

            <Button
              @click="submit"
              label="Permintaan Password baru"
              :disabled="form.processing"
              class="w-full p-3 text-xl"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

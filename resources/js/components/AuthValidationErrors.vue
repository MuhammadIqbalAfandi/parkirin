<script setup>
import { computed, ref, watch } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'

const errors = computed(() => usePage().props.value.errors)
const hasErrors = computed(() => Object.keys(errors.value).length > 0)

const messageDisplay = ref({})

const onClose = () => (messageDisplay.value = { display: 'none' })

watch(
  errors,
  () => {
    messageDisplay.value = { display: '' }
  },
  { deep: true }
)
</script>

<template>
  <Message v-if="hasErrors" severity="error" :style="messageDisplay" @close="onClose()">
    <ul class="m-0">
      <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
    </ul>
  </Message>
</template>

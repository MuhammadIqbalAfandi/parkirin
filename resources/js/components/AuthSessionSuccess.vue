<script setup>
import { computed, watch, ref } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'

const status = computed(() => usePage().props.value.status)

const messageDisplay = ref({})

const onClose = () => (messageDisplay.value = { display: 'none' })

watch(
  status,
  () => {
    messageDisplay.value = { display: '' }
  },
  { deep: true }
)
</script>

<template>
  <Message v-if="status" severity="success" :style="messageDisplay" @close="onClose()">{{ status }}</Message>
</template>

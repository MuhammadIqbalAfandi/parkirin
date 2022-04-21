<script setup>
defineProps({
  message: {
    type: String,
    default: null,
  },
  header: {
    type: String,
    default: 'Peringatan',
  },
  cancelLabel: {
    type: String,
    default: 'Tidak',
  },
  agreeLabel: {
    type: String,
    default: 'Ya',
  },
})

defineEmits(['cancel', 'agree'])
</script>

<template>
  <Dialog :header="header" :style="{ width: '450px' }" :modal="true" :breakpoints="{ '960px': '75vw' }">
    <div class="flex align-items-center justify-content-center">
      <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
      <p v-if="message">{{ message }}</p>
    </div>

    <template #footer>
      <Button :label="cancelLabel" icon="pi pi-times" class="p-button-text" @click="$emit('cancel')" />

      <Button :label="agreeLabel" icon="pi pi-check" class="p-button-text" @click="$emit('agree')" />
    </template>
  </Dialog>
</template>

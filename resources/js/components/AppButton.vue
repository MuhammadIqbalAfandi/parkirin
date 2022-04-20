<script setup>
import { Link } from '@inertiajs/inertia-vue3'

defineProps({
  inertiaLink: {
    type: Boolean,
    default: true,
  },
  icon: String,
  label: String,
  classButton: String,
})
</script>

<template>
  <Link v-if="inertiaLink" class="p-button p-component" as="button" type="button">
    <span v-if="icon" class="p-button-icon p-button-icon-left" :class="icon"></span>
    <span v-if="label" class="p-button-label">{{ label }}</span>
  </Link>
  <a v-else>
    <Button :class="classButton">
      <span v-if="icon" class="p-button-icon p-button-icon-left" :class="icon"></span>
      <span v-if="label" class="p-button-label">{{ label }}</span>
    </Button>
  </a>
</template>

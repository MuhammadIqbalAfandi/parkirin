<script setup>
import AppSubSidebar from '@/components/AppSubSidebar.vue'

defineProps({
  model: Array,
})

const emits = defineEmits(['menuitem-click'])

const onKeyDown = (event) => {
  const nodeElement = event.target
  if (event.code === 'Enter' || event.code === 'Space') {
    nodeElement.click()
    event.preventDefault()
  }
}

const onMenuItemClick = (event) => {
  emits('menuitem-click', event)
}
</script>

<template>
  <div class="layout-menu-container">
    <AppSubSidebar
      class="layout-menu"
      :items="model"
      :root="true"
      @keydown="onKeyDown"
      @menuitem-click="onMenuItemClick"
    />
  </div>
</template>

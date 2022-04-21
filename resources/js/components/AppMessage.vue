<script setup>
import { ref, watch, computed } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'

const flashMessage = computed(() => usePage().props.value.flash)

const messageDisplay = ref({})

const onClose = () => {
  usePage().props.value.errors = {}

  usePage().props.value.flash.success = null

  usePage().props.value.flash.error = null

  messageDisplay.value = { display: 'none' }
}

watch(
  flashMessage,
  () => {
    messageDisplay.value = { display: '' }
  },
  { deep: true }
)
</script>

<template>
  <div class="grid">
    <div class="col-12">
      <Message v-if="$page.props.flash.success" severity="success" :style="messageDisplay" @close="onClose">
        {{ $page.props.flash.success }}
      </Message>

      <template v-if="$page.props.flash.error || Object.keys($page.props.errors).length > 0">
        <Message v-if="$page.props.flash.error" severity="error" :style="messageDisplay" @close="onClose">
          {{ $page.props.flash.error }}
        </Message>

        <Message v-else severity="error" :style="messageDisplay" @close="onClose">
          <div v-if="Object.keys($page.props.errors).length === 1">Ditemukan satu error pada form</div>
          <div v-else>Ditemukan {{ Object.keys($page.props.errors).length }} error pada form</div>
        </Message>
      </template>
    </div>
  </div>
</template>

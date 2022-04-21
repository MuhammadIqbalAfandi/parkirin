<script setup>
import { computed } from 'vue'

const props = defineProps({
  type: {
    type: String,
    default: 'text',
  },
  label: {
    type: String,
    required: true,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  placeholder: {
    type: String,
    required: true,
  },
  error: {
    type: String,
    default: null,
  },
  modelValue: null,
})

defineEmits(['update:modelValue'])

const isError = computed(() => (props.error ? true : false))

const forLabel = computed(() => props.label.toLowerCase().replace(/\s+/g, '-'))

const ariaDescribedbyLabel = computed(() => props.label.toLowerCase().replace(/\s+/g, '-') + '-help')
</script>

<template>
  <div class="field">
    <label :for="forLabel">{{ label }}</label>

    <InputText
      class="w-full"
      :class="{ 'p-invalid': isError }"
      :id="forLabel"
      :aria-describedby="ariaDescribedbyLabel"
      :model-value="modelValue"
      :type="type"
      :placeholder="placeholder"
      :value="modelValue"
      :disabled="disabled"
      @input="$emit('update:modelValue', $event.target.value)"
    />

    <small v-if="error" :id="ariaDescribedbyLabel" :class="{ 'p-error': isError }">
      {{ error }}
    </small>
  </div>
</template>

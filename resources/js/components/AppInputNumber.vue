<script setup>
import { computed } from 'vue'

const props = defineProps({
  label: {
    type: String,
    required: true,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  type: {
    type: String,
    default: 'text',
  },
  mode: {
    type: String,
    default: 'decimal',
  },
  incrementButtonClass: {
    type: String,
    default: null,
  },
  decrementButtonClass: {
    type: String,
    default: null,
  },
  incrementButtonIcon: {
    type: String,
    default: 'pi pi-angle-up',
  },
  decrementButtonIcon: {
    type: String,
    default: 'pi pi-angle-down',
  },
  showButtons: {
    type: Boolean,
    default: false,
  },
  buttonLayout: {
    type: String,
    default: 'stacked',
  },
  min: {
    type: Number,
    default: null,
  },
  max: {
    type: Number,
    default: null,
  },
  step: {
    type: Number,
    default: 1,
  },
  prefix: {
    type: String,
    default: null,
  },
  suffix: {
    type: String,
    default: null,
  },
  placeholder: {
    type: String,
    required: true,
  },
  useGrouping: {
    type: Boolean,
    default: true,
  },
  currency: {
    type: String,
    default: undefined,
  },
  locale: {
    type: String,
    default: undefined,
  },
  error: {
    type: String,
    default: null,
  },
  currencyDisplay: {
    type: String,
    default: undefined,
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

    <InputNumber
      class="w-full"
      input-class="w-full"
      :currency="currency"
      :currency-display="currencyDisplay"
      :locale="locale"
      :class="{ 'p-invalid': isError }"
      :id="forLabel"
      :aria-describedby="ariaDescribedbyLabel"
      :type="type"
      :placeholder="placeholder"
      :model-value="modelValue"
      :disabled="disabled"
      :prefix="prefix"
      :suffix="suffix"
      :step="step"
      :min="min"
      :max="max"
      :mode="mode"
      :use-grouping="useGrouping"
      :show-buttons="showButtons"
      :button-layout="buttonLayout"
      :increment-button-class="incrementButtonClass"
      :decrement-button-class="decrementButtonClass"
      :increment-button-icon="incrementButtonIcon"
      :decrement-button-icon="decrementButtonIcon"
      @input="$emit('update:modelValue', $event.value)"
    />

    <small v-if="error" :id="ariaDescribedbyLabel" :class="{ 'p-error': isError }">
      {{ error }}
    </small>
  </div>
</template>

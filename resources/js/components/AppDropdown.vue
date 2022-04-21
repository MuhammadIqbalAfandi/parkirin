<script setup>
import { computed } from 'vue'

const props = defineProps({
  label: {
    type: String,
    required: true,
  },
  optionLabel: {
    type: String,
    default: 'label',
  },
  optionValue: {
    type: String,
    default: 'value',
  },
  optionDisabled: {
    type: String,
    default: 'disabled',
  },
  options: {
    type: Array,
    required: true,
  },
  placeholder: {
    type: String,
    required: true,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  error: {
    type: String,
    default: null,
  },
  modelValue: null,
})

defineEmits(['update:modelValue'])

const isError = computed(() => (props.error ? true : false))

const forLabel = computed(() => (props.label ? props.label.toLowerCase().replace(/\s+/g, '-') : null))

const ariaDescribedbyLabel = computed(() =>
  props.label ? props.label.toLowerCase().replace(/\s+/g, '-') + '-help' : null
)

const selectedDropdownLabel = (value) => {
  const result = props.options.find((option) => option[props.optionValue] == value)
  if (result) {
    return result[props.optionLabel]
  }
}
</script>

<template>
  <div class="field">
    <label v-if="label" :for="forLabel">{{ label }}</label>

    <Dropdown
      class="w-full"
      :class="{ 'p-invalid': isError }"
      :id="forLabel"
      :aria-describedby="ariaDescribedbyLabel"
      :option-disabled="optionDisabled"
      :option-label="optionLabel"
      :option-value="optionValue"
      :placeholder="placeholder"
      :options="options"
      :model-value="modelValue"
      :disabled="disabled"
      @change="$emit('update:modelValue', $event.value)"
    >
      <template #value="slotProps">
        <div v-if="slotProps.value">
          {{ selectedDropdownLabel(slotProps.value) }}
        </div>

        <div v-else>
          {{ slotProps.placeholder }}
        </div>
      </template>

      <template #option="{ option, index }">
        <slot name="option" :option="option" :index="index" />
      </template>
    </Dropdown>

    <small v-if="error" :id="ariaDescribedbyLabel" :class="{ 'p-error': isError }">
      {{ error }}
    </small>
  </div>
</template>

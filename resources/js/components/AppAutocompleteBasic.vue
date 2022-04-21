<script setup>
import { computed } from 'vue'

const props = defineProps({
  field: {
    type: String,
    required: true,
  },
  suggestions: {
    type: Array,
    required: true,
  },
  empty: {
    type: Boolean,
    default: false,
  },
  label: {
    type: String,
    required: true,
  },
  dropdown: {
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

defineEmits(['complete', 'itemSelect', 'update:modelValue'])

const isError = computed(() => (props.error ? true : false))

const forLabel = computed(() => props.label.toLowerCase().replace(/\s+/g, '-'))

const ariaDescribedbyLabel = computed(() => props.label.toLowerCase().replace(/\s+/g, '-') + '-help')
</script>

<template>
  <div class="field">
    <label :for="forLabel">{{ label }}</label>

    <AutoComplete
      class="w-full"
      inputClass="w-full"
      :model-value="modelValue"
      :aria-describedby="ariaDescribedbyLabel"
      :id="forLabel"
      :class="{ 'p-invalid': isError }"
      :field="field"
      :placeholder="placeholder"
      :suggestions="suggestions"
      :auto-highlight="true"
      :dropdown="dropdown"
      @input="$emit('update:modelValue', $event.target.value)"
      @item-select="$emit('itemSelect', $event)"
      @complete="$emit('complete', $event)"
    >
      <template #item="slotProps">
        <slot name="item" :item="slotProps.item" />
      </template>
    </AutoComplete>

    <div class="flex flex-column">
      <small
        v-if="error"
        class="mt-1"
        :class="{ 'mb-2': suggestions.length === 0 || (modelValue.length === 0 && empty), 'p-error': isError }"
        :id="ariaDescribedbyLabel"
      >
        {{ error }}
      </small>

      <small v-if="suggestions.length === 0 || (modelValue.length === 0 && empty)" class="mt-1">
        <slot v-if="empty" name="empty" />
      </small>
    </div>
  </div>
</template>

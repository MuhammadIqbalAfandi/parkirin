<script setup>
defineProps({
  data: {
    type: Object,
    required: true,
  },
})
</script>

<template>
  <Card class="h-full">
    <template #content>
      <div class="flex justify-content-between mb-3">
        <div>
          <span class="block text-500 font-medium mb-3">{{ data.title }}</span>
          <div v-if="data.value" class="text-900 font-medium text-xl">{{ data.value }}</div>
        </div>
        <div
          class="flex align-items-center justify-content-center bg-orange-100 border-round"
          style="width: 2.5rem; height: 2.5rem"
        >
          <i class="text-orange-500 text-xl" :class="data.icon"></i>
        </div>
      </div>
      <span class="text-green-500 font-medium">{{ data.amount }} </span>
      <span class="text-500"> {{ ' ' + data.amountLabel }}</span>
    </template>
  </Card>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/inertia-vue3'

defineProps({
  items: Array,
  root: {
    type: Boolean,
    default: false,
  },
})

const emits = defineEmits(['menuitem-click'])

const activeIndex = ref(null)

const onMenuItemClick = (event, item, index) => {
  if (item.disabled) {
    event.preventDefault()
    return
  }
  if (!item.to && !item.url) {
    event.preventDefault()
  }

  if (item.command) {
    item.command({ originalEvent: event, item: item })
  }

  activeIndex.value = index === activeIndex.value ? null : index

  emits('menuitem-click', {
    originalEvent: event,
    item: item,
  })
}

const visible = (item) => {
  return typeof item.visible === 'function' ? item.visible() : item.visible !== false
}
</script>

<template>
  <ul v-if="items">
    <template v-for="(item, i) of items">
      <li
        v-if="visible(item) && !item.separator"
        :key="item.label || i"
        :class="[
          { 'layout-menuitem-category': root, 'active-menuitem': activeIndex === i && !item.to && !item.disabled },
        ]"
        role="none"
      >
        <template v-if="root">
          <div class="layout-menuitem-root-text" :aria-label="item.label">{{ item.label }}</div>
          <AppSubSidebar
            :items="visible(item) && item.items"
            @menuitem-click="$emit('menuitem-click', $event)"
          ></AppSubSidebar>
        </template>
        <template v-else>
          <Link
            v-if="item.to"
            v-ripple
            role="menuitem"
            :href="item.to"
            :class="[
              item.class,
              'p-ripple',
              {
                'p-disabled': item.disabled,
                'router-link-active': $page.component.startsWith(item.component) || $page.url.startsWith(item.to),
                'router-link-exact-active': $page.component.startsWith(item.component) || $page.url.startsWith(item.to),
              },
            ]"
            :style="item.style"
            :target="item.target"
            :aria-label="item.label"
            @click="onMenuItemClick($event, item, i)"
          >
            <i :class="item.icon"></i>
            <span>{{ item.label }}</span>
            <i v-if="item.items" class="pi pi-fw pi-angle-down menuitem-toggle-icon"></i>
            <Badge v-if="item.badge" :value="item.badge"></Badge>
          </Link>

          <a
            v-if="!item.to"
            v-ripple
            role="menuitem"
            :style="item.style"
            :class="[item.class, 'p-ripple', { 'p-disabled': item.disabled }]"
            :href="item.url || '#'"
            :target="item.target"
            :aria-label="item.label"
            @click="onMenuItemClick($event, item, i)"
          >
            <i :class="item.icon"></i>
            <span>{{ item.label }}</span>
            <i v-if="item.items" class="pi pi-fw pi-angle-down menuitem-toggle-icon"></i>
            <Badge v-if="item.badge" :value="item.badge"></Badge>
          </a>

          <transition name="layout-submenu-wrapper">
            <AppSubSidebar
              v-show="activeIndex === i"
              :items="visible(item) && item.items"
              @menuitem-click="$emit('menuitem-click', $event)"
            ></AppSubSidebar>
          </transition>
        </template>
      </li>
      <li
        class="p-menu-separator"
        v-if="visible(item) && item.separator"
        role="separator"
        :style="item.style"
        :key="'separator' + i"
      ></li>
    </template>
  </ul>
</template>

<script setup>
import { ref, computed, onBeforeUpdate } from 'vue'
import AppTopBar from '@/components/AppTopBar.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppFooter from '@/components/AppFooter.vue'
import AppMessage from '@/components/AppMessage.vue'

import menu from '@/utils/menu'

const mobileMenuActive = ref(false)

const staticMenuInactive = ref(false)

const menuClick = ref(false)

const isDesktop = () => window.innerWidth >= 992

const addClass = (element, className) => {
  if (element.classList) element.classList.add(className)
  else element.className += ' ' + className
}

const removeClass = (element, className) => {
  if (element.classList) element.classList.remove(className)
  else
    element.className = element.className.replace(
      new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'),
      ' '
    )
}

onBeforeUpdate(() => {
  if (mobileMenuActive.value) {
    addClass(document.body, 'body-overflow-hidden')
  } else {
    removeClass(document.body, 'body-overflow-hidden')
  }
})

const containerClass = computed(() => {
  return [
    'layout-wrapper',
    'layout-static',
    {
      'layout-static-sidebar-inactive': staticMenuInactive.value,
      'layout-mobile-sidebar-active': mobileMenuActive.value,
    },
  ]
})

const onWrapperClick = () => {
  if (!menuClick.value) {
    mobileMenuActive.value = false
  }

  menuClick.value = false
}

const onMenuToggle = (event) => {
  menuClick.value = true

  if (isDesktop()) {
    staticMenuInactive.value = !staticMenuInactive.value
  } else {
    mobileMenuActive.value = !mobileMenuActive.value
  }

  event.preventDefault()
}

const onSidebarClick = () => {
  menuClick.value = true
}

const onMenuItemClick = (event) => {
  if (event.item && !event.item.items) {
    mobileMenuActive.value = false
  }
}
</script>

<template>
  <div :class="containerClass" @click="onWrapperClick">
    <AppTopBar @menu-toggle="onMenuToggle" />

    <div class="layout-sidebar" @click="onSidebarClick">
      <AppSidebar :model="menu[$page.props.auth.user.role_id]" @menuitem-click="onMenuItemClick" />
    </div>

    <div class="layout-main-container">
      <div class="layout-main">
        <AppMessage />

        <slot />
      </div>

      <AppFooter />
    </div>

    <Transition name="layout-mask">
      <div class="layout-mask p-component-overlay" v-if="mobileMenuActive"></div>
    </Transition>
  </div>
</template>

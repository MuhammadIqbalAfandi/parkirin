<script setup>
import { Link } from '@inertiajs/inertia-vue3'
</script>

<template>
  <div class="flex flex-column justify-content-center align-items-center h-screen">
    <h1>Web App Parkirin</h1>
    <Link href="/logout" method="post" as="button" class="p-button p-component p-button-rounded"> Logout </Link>
  </div>
</template>

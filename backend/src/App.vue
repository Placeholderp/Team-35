<template>
  <div id="app">
    <router-view></router-view>
    <NotificationSystem />
  </div>
</template>

<script setup>
import NotificationSystem from './components/core/Notification/NotificationSystem.vue'
</script>

<style>
/* Your styles here */
</style>
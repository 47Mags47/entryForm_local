<script setup>
defineProps({
    status: String,
});
</script>

<template>
    <div :class="['circle', status]" />
</template>

<style lang="sass">
.circle
  width: 15px
  height: 15px
  border-radius: 50%
</style>

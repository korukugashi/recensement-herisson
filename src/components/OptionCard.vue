<template>
  <div :class="{'is-selected': isSelected}" @click="$emit(isSelected ? 'uncheck' : 'check', option.label)">
    <img :src="`/icons/${option.img}`" :alt="option.label" />
    <div style="font-size: 0.9rem">{{ option.label }}</div>
  </div>
</template>

<script>
export default {
  props: ["option", "isSelected"],
}
</script>
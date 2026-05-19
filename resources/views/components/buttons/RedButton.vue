<script>
import { default as BaseButton } from "../base/BaseButton.vue";

export default {
    components: {
        BaseButton,
    },
};
</script>

<template>
    <BaseButton className="red-button" v-bind="$attrs">
        <slot />
    </BaseButton>
</template>

<style lang="sass" scoped>
.red-button
    background: var(--red-button-background-color)
    &:hover
        background: var(--red-button-background-color-hover)
</style>

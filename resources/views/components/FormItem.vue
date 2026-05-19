<script>
import { default as FormError } from "./FormError.vue";

export default {
    components: {
        FormError,
    },

    props: {
        name: String,
    },
};
</script>

<template>
    <div :class="['form-item', name]">
        <slot />
        <FormError :name="name" />
    </div>
</template>

<style lang="sass">
.form-item
    display: flex
    flex-direction: column
    label
        padding-left: 0px
        color: var(--label-color)
        font-weight: 500
        padding-top: 10px
        padding-bottom: 3px
    &.inline
        flex-direction: row
        align-items: center
        label
            padding: 5px 0
            flex: 1
</style>

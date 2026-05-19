<script>
import { default as Label } from "./Label.vue";

export default {
    components: {
        Label,
    },

    props: {
        name: String,
        label: String,
    },
};
</script>

<template>
    <div class="form-group-container">
        <Label name="group" :labelText="label" />
        <div :class="['form-group', name]">
            <slot />
        </div>
    </div>
</template>

<style lang="sass">
.form-group-container
    display: flex
    justify-items: columns
    flex-direction: column
    gap: 5px
    .form-group
        display: flex
        border: 1px solid var(--input-border-color)
        border-radius: 7px
        padding: 20px
    .label.group
        font-size: 1.3rem
        color: var(--label-color)
        display: flex
        justify-content: center
</style>

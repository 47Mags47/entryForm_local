<script>
import { default as BaseForm } from "../base/BaseForm.vue";

export default {
    components: {
        BaseForm,
    },

    props: {
        header: String,
    },
};
</script>

<template>
    <BaseForm :header="header" className="horizontal-form">
        <slot />
    </BaseForm>
</template>

<style lang="sass">
.horizontal-form-box
    display: flex
    justify-content: center
    align-items: start

.horizontal-form
    min-width: 500px
    padding: 25px
    margin-top: 40px

    display: flex
    flex-direction: column
    gap: 5px

    border-radius: 7px
    box-shadow: 4px 4px 32px 16px #eee

    .form-header
        text-align: center
        padding-bottom: 20px
        color: var(--text-color)
        font-weight: bold
        font-size: 2rem

    .form-content
        display: flex
        gap: 30px

    .form-group
        display: flex
        flex-direction: column
        justify-content: center
        flex: 1

    .form-buttons
        display: flex
        justify-content: end
        align-items: center
</style>

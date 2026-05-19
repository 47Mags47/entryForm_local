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
    <BaseForm :header="header" className="vertical-form">
        <slot />
        <template #info>
            <slot name="info" />
        </template>
        <template #buttons>
            <slot name="buttons" />
        </template>
    </BaseForm>
</template>

<style lang="sass">
.vertical-form-box
    display: flex
    justify-content: center
    align-items: start
    background: rgb(251,251,251)

.vertical-form
    min-width: 500px
    max-width: 550px
    padding: 25px
    margin-top: 40px

    display: flex
    flex-direction: column
    gap: 10px

    border-radius: 7px
    box-shadow: 4px 4px 32px 16px #eee

    .form-header
        text-align: center
        padding-bottom: 20px
        color: var(--text-color)
        font-weight: bold
        font-size: 23px

    .form-content
        display: flex
        flex-direction: column
        gap: 5px

    .form-buttons
        display: flex
        justify-content: space-between
        align-items: center
        gap: 10px

    .button
        flex: 0
        &.ico
            width: 45px
            height: 30px
    .form-backside
        display: flex
        justify-content: start
        align-items: center
        gap: 10px
        color: var(--text-color)
        .reset
            font-size: 0.95rem
            &:hover
                border-bottom: 1px solid #83adf0
</style>

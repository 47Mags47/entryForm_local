<script>
import { default as BlueButton } from "../buttons/BlueButton.vue";
import { default as FormError } from "../FormError.vue";

export default {
    components: {
        BlueButton,
        FormError,
    },

    props: {
        className: String,
        header: String,
        sbm: String,
        handleSubmit: Function,
    },

    computed: {
        boxClassName() {
            return this.className ? `${this.className}-box` : "";
        },
    },
};
</script>

<template>
    <div :class="['form-container', boxClassName]">
        <form @submit="handleSubmit" :class="['form', className]">
            <h3 v-if="header" class="form-header">
                {{ header }}
            </h3>

            <div class="form-errors">
                <FormError name="form" />
            </div>

            <div class="form-content">
                <slot />
            </div>

            <div class="form-buttons">
                <slot name="buttons" />
                <BlueButton v-if="sbm" type="submit">
                    {{ sbm }}
                </BlueButton>
            </div>

            <div class="form-backside">
                <slot name="info" />
            </div>
        </form>
    </div>
</template>

<style lang="sass" scoped>
.form-container
    width: 100%
    height: 100%
</style>

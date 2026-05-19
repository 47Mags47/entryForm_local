<script>
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

export default {
    props: {
        name: {
            type: String,
            default: "",
        },
    },

    computed: {
        errors() {
            return usePage().props.errors;
        },
        errorKeys() {
            let processedName = this.name;

            if (processedName.includes("[")) {
                processedName = processedName
                    .replaceAll("[", ".")
                    .replaceAll("]", "");
            }

            const reg = new RegExp(
                `(^${processedName}$)|(^${processedName}\\.[0-9]{1,99}$)|(^${processedName}.*\\.[0-9]{1,99}$)`,
            );

            return Object.keys(this.errors).filter((item) => reg.test(item));
        },
        errorMessages() {
            return this.errorKeys.map((key) => this.errors[key]);
        },
    },
};
</script>

<template>
    <ul v-if="errorMessages.length > 0" class="error-list">
        <li v-for="(message, index) in errorMessages" :key="index">
            {{ message }}
        </li>
    </ul>
</template>

<style lang="sass" scoped>
.error-list
    margin: 4px 0 0 0
    padding: 5px
    list-style: none
    border-radius: 7px
    border: 1px solid var( --form-error-color)
    background: var(--form-error-background)
    color: var( --form-error-color)
    font-size: 13px
    line-height: 1.4

    li
        position: relative
        padding-left: 16px

        &::before
            content: "⚠"
            position: absolute
            left: 0
            top: 0
            font-size: 12px
            line-height: 1.4
            color: var(--form-error-color)
</style>

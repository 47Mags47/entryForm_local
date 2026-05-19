<script>
import { default as FormItem } from "../FormItem.vue";
import { default as Label } from "../Label.vue";

export default {
    components: {
        FormItem,
        Label,
    },

    props: {
        rows: Number,
        value: {
            type: [String, Number, null],
            default: "",
        },
        placeholder: {
            type: String,
            default: "",
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        autocomplete: {
            type: String,
            default: "",
        },
        id: String,
        name: String,
        label: String,
    },

    emits: ["click", "update:value"],

    computed: {
        inputId() {
            this.id ?? this.name;
        },
        placeholderText() {
            this.placeholder ?? "";
        },
    },
};
</script>

<template>
    <FormItem :name="name">
        <Label :labelText="label" />
        <textarea
            :rows="rows"
            :id="inputId"
            :name="name"
            :value="value ?? ''"
            @input="$emit('update:value', $event.target.value)"
            :placeholder="placeholderText"
            :disabled="disabled"
            @click="$emit('click', $event)"
            v-bind="$attrs"
        />
    </FormItem>
</template>

<style lang="sass" scoped>
textarea
    @include input()
</style>

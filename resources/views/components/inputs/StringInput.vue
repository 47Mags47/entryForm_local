<script>
import { default as FormItem } from "../FormItem.vue";
import { default as Label } from "../Label.vue";

export default {
    components: {
        Label,
        FormItem,
    },

    props: {
        type: {
            type: String,
            default: "text",
        },
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
        id: {
            type: String,
            default: null,
        },
        name: String,
        label: String,
        readonly: Boolean,
    },

    emits: ["update:value", "click", "keydown"],

    computed: {
        inputId() {
            this.id ?? this.name;
        },
    },
};
</script>

<template>
    <FormItem :name="name">
        <Label :labelText="label" />
        <input
            :type="type"
            :id="inputId"
            :name="name"
            :value="value ?? ''"
            @input="$emit('update:value', $event.target.value)"
            :placeholder="placeholder"
            :disabled="disabled"
            :autocomplete="autocomplete"
            :readonly="readonly"
            @keydown="$emit('keydown', $event)"
            @click="$emit('click', $event)"
            v-bind="$attrs"
        />
    </FormItem>
</template>

<style lang="sass">
input, .input
    height: 30px
    @include input
</style>

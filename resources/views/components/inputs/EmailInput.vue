<script>
import { default as FormItem } from "../FormItem.vue";
import { default as Label } from "../Label.vue";

export default {
    components: {
        Label,
        FormItem,
    },

    props: {
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
        label: {
            type: String,
            default: null,
        },
        readonly: Boolean,
    },

    emits: ["update:value", "click", "keydown"],

    methods: {
        onInput(event) {
            let value = event.target.value

            this.$emit('update:value', value)
        }
    },

    computed: {
        inputId() {
            return this.id ?? this.name;
        },
    },
};
</script>

<template>
    <FormItem :name="name">
        <Label :labelText="label" v-if="label !== null" />
        <input
            type="email"
            :id="inputId"
            :name="name"
            :value="value"
            @input="onInput"
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

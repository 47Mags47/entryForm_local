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

    methods: {
        inputHandler(event) {
            this.$emit('update:value', event.target.value)
        }
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
        <Label v-if="label" :labelText="label" />
        <textarea
            :rows="rows"
            :id="inputId"
            :name="name"
            :value="value ?? ''"
            @input="inputHandler"
            :placeholder="placeholderText"
            :disabled="disabled"
            @click="$emit('click', $event)"
            v-bind="$attrs"
            maxlength="255"
        />

        <span class="words-count"> {{ value?.length ?? 0 }}/255 </span>
    </FormItem>
</template>

<style lang="sass" scoped>
textarea
    @include input()

.form-item
    position: relative

.words-count
    position: absolute
    color: #aaa
    bottom: 3px
    right: 4px
    font-size: 14px
</style>

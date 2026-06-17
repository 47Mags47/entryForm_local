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
        onBeforeInput(event) {
            if (this.name !== 'phone') return

            if (event.data && /\D/.test(event.data)) {
                event.preventDefault()
            }
        },
        onInput(event) {
            this.$emit('update:value', event.target.value)
        }
    },

    computed: {
        inputId() {
            this.id ?? this.name;
        },
        maskPhone() {
            let digits = this.value.replace(/\D/g, '')

            // нормализация РФ
            if (digits.startsWith('8')) {
                digits = '7' + digits.slice(1)
            }
            if (!digits.startsWith('7')) {
                digits = '7' + digits
            }

            digits = digits.slice(0, 11)

            let result = '+7'

            if (digits.length > 1) {
                result += ' (' + digits.slice(1, 4)
            }
            if (digits.length >= 4) {
                result += ') ' + digits.slice(4, 7)
            }
            if (digits.length >= 7) {
                result += ' ' + digits.slice(7, 9)
            }
            if (digits.length >= 9) {
                result += '-' + digits.slice(9, 11)
            }

            return result
        }
    },
};
</script>

<template>
    <FormItem :name="name">
        <Label :labelText="label" v-if="label !== null" />
        <input
            type="text"
            :id="inputId"
            :name="name"
            :value="name === 'phone' ? ( maskPhone ?? '' ) : value"
            @beforeinput="onBeforeInput($event)"
            @input="onInput($event)"
            :maxlength="name === 'phone' ? '18' : null"
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

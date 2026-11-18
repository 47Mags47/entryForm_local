<script>
import BaseSelect from "../BaseSelect.vue";

export default {
    components: {
        BaseSelect,
    },

    props: {
        // INPUT
        name: {
            type: String,
            default: "",
        },
        value: {
            type: Number,
            default: null,
        },
        labelKey: {
            type: String,
            default: "name",
        },
        valueKey: {
            type: String,
            default: "id",
        },

        // HANDLERS
        onChange: {
            type: Function,
            default: () => {},
        },
    },

    data() {
        return {
            isOpen: false,
            localValue: null,
        };
    },

    methods: {
        optionClickHandler(optionValue) {
            this.localValue = optionValue
        },
        clearHandler(e, v) {
            this.localValue = null
            this.onChange(e, v)
        }
    },

    watch: {
        value: {
            handler(val) {
                this.localValue = val
            },
            immediate: true
        },
    },
};
</script>

<template>
    <BaseSelect
        type="single"
        :value="localValue"
        :label-key
        :value-key
        :on-clear="clearHandler"
        :on-option-click="optionClickHandler"
    >
        <template #option="{ option, selected, onKey, onOptionClick }">
            <input type="radio"
                :value="localValue"
                :checked="selected"
                :name="`${name}[]`"
                @change="(event) => onChange(event, option)"
                @click="onOptionClick"
                @keydown="onKey"
            />
            <span>{{ Object.get(option, labelKey) }}</span>
        </template>
    </BaseSelect>
</template>

<style lang="sass" scoped>
input[type='radio']
    width: fit-content
</style>

<script>
import BaseSelect from '../BaseSelect.vue';

export default {
    components: {
        BaseSelect,
    },

    data() {
        return {
            localValue: []
        }
    },

    props: {
        // INPUT
        name: {
            type: String,
            default: ''
        },
        value: {
            type: Array,
            default: () => ([])
        },
        labelKey: {
            type: String,
            default: 'name'
        },
        valueKey: {
            type: String,
            default: "id",
        },

        // HANDLERS
        onChange: {
            type: Function,
            default: () => {}
        },
    },

    methods: {
        optionClickHandler(optionValue) {
            if (this.localValue.includes(optionValue))
                this.localValue = this.localValue.filter(item => item !== optionValue);
            else
                this.localValue = [...this.localValue, optionValue];

        },
        clearHandler(e, v) {
            this.localValue = []
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
}
</script>

<template>
    <BaseSelect
        type="multi"
        :value="localValue"
        :label-key
        :value-key
        :on-clear="clearHandler"
        :on-option-click="optionClickHandler"
    >
        <template #option="{ option, selected, onKey, onOptionClick }">
            <input type="checkbox"
                :value="selected"
                :checked="selected"
                :name="`${name}[${Object.get(option, valueKey)}]`"
                @change="(e, v) => onChange(e, localValue)"
                @click="onOptionClick"
                @keydown="onKey"
            />
            <span> {{ Object.get(option, labelKey) }} </span>
        </template>
    </BaseSelect>
</template>

<style lang="sass" scoped>
input[type='checkbox']
    width: fit-content
</style>

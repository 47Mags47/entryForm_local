<script>
import { default as SelectList } from "./SelectList.vue";
import { default as SelectInput } from "./SelectInput.vue";
import { default as Label } from "../../Label.vue";
import { default as FormItem } from "../../FormItem.vue";

export default {
    components: {
        SelectList,
        SelectInput,
        Label,
        FormItem,
    },

    props: {
        modelValue: [String, Number],
        options: {
            type: Array,
            required: true,
        },
        label: String,
        name: String,
        disabled: Boolean,
        placeholder: String,
    },

    emits: ["update:modelValue"],

    data() {
        return {
            isOpen: false,
            selectWrapper: null,
        };
    },

    computed: {
        selectedLabel() {
            const selectedOption = this.options.find(
                (option) => option.value === this.modelValue,
            );
            return selectedOption ? selectedOption.label : "";
        },
    },

    methods: {
        toggleDropdown(event) {
            event.stopPropagation();
            if (!this.disabled) {
                this.isOpen = !this.isOpen;
            }
        },

        closeDropdown() {
            this.isOpen = false;
        },

        selectOption(option) {
            this.$emit("update:modelValue", option.value);
            this.isOpen = false;
        },

        handleClickOutside(event) {
            if (
                this.$refs.selectWrapper &&
                !this.$refs.selectWrapper.contains(event.target)
            ) {
                this.closeDropdown();
            }
        },
    },

    mounted() {
        document.addEventListener("click", this.handleClickOutside);
    },

    beforeUnmount() {
        document.removeEventListener("click", this.handleClickOutside);
    },
};
</script>

<template>
    <FormItem :name="name">
        <Label :labelText="label" />
        <div
            ref="selectWrapper"
            class="select-wrapper"
            :class="{ 'is-disabled': disabled }"
            @click="toggleDropdown"
        >
            <SelectInput
                :selected-label="selectedLabel"
                :placeholder="placeholder"
                :is-open="isOpen"
                :disabled="disabled"
            />
            <SelectList
                v-show="isOpen"
                :options="options"
                :model-value="modelValue"
                @select="selectOption"
            />
        </div>

        <input v-if="name" type="hidden" :name="name" :value="modelValue" />
    </FormItem>
</template>

<style lang="sass">
.select-wrapper
  position: relative
  width: 100%
</style>

<script>
import CalendarIco from "../../icons/CalendarIco.vue";

export default {
    components: {
        CalendarIco,
    },
    props: {
        modelValue: String,
        disabled: Boolean,
        placeholder: {
            type: String,
            default: "ДД.MM.ГГГГ",
        },
    },

    emits: ["focus", "confirm"],

    data() {
        return {
            internalValue: this.modelValue || "",
            isPopupClick: false,
        };
    },

    watch: {
        modelValue: {
            handler(val) {
                this.internalValue = val || "";
            },
            immediate: true,
        },
    },

    methods: {
        onInput(e) {
            this.internalValue = e.target.value;
        },

        onFocus() {
            this.isPopupClick = false;
            this.$emit("focus");
        },

        onBlur(e) {
            if (this.isPopupClick) {
                this.isPopupClick = false;
                return;
            }

            setTimeout(() => {
                const active = document.activeElement;
                if (active && active.closest(".datepicker-popup-teleport")) {
                    return;
                }
                this.$emit("confirm", this.internalValue);
            }, 100);
        },

        onKeyDown(e) {
            if (e.key === "Enter") {
                e.preventDefault();
                this.$emit("confirm", this.internalValue);
            }
        },

        markAsPopupClick() {
            this.isPopupClick = true;
        },
    },
};
</script>

<template>
    <div class="date-input-wrapper">
        <input
            type="date"
            :value="internalValue"
            :disabled="disabled"
            :placeholder="placeholder"
            @input="onInput"
            @focus="onFocus"
            @blur="onBlur"
            @keydown="onKeyDown"
            max="9999-12-31"
            class="custom-date-input"
        />
        <div class="calendar-icon">
            <CalendarIco />
        </div>
    </div>
</template>

<style lang="sass" scoped>
.date-input-wrapper
    position: relative
    display: inline-block
    width: 100%


.custom-date-input
    @include input()
    width: 100%
    padding: 8px 40px 8px 12px;


.calendar-icon
    position: absolute
    right: 12px
    top: 50%
    transform: translateY(-50%)
    color: #6b7280
    cursor: pointer
    display: flex
    align-items: center
    justify-content: center

.custom-date-input
    -webkit-appearance: none
    -moz-appearance: none
    appearance: none
    background-image: none

.custom-date-input::-webkit-calendar-picker-indicator
    display: none
    -webkit-appearance: none
</style>

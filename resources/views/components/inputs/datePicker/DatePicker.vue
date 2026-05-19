<script>
import dayjs from "dayjs";
import DateInput from "./DateInput.vue";
import DatePickerPopup from "./DatePickerPopup.vue";

export default {
    components: {
        DateInput,
        DatePickerPopup,
    },

    props: {
        modelValue: [String, Date, null],
        disabled: Boolean,
    },

    emits: ["update:modelValue"],

    data() {
        return {
            internalValue: "",
            isEditing: false,
            showPopup: false,
        };
    },

    watch: {
        modelValue: {
            handler(val) {
                if (!this.isEditing) {
                    this.initializeValue();
                }
            },
            immediate: true,
        },
    },

    mounted() {
        document.addEventListener("mousedown", this.handleClickOutside);
    },

    beforeUnmount() {
        document.removeEventListener("mousedown", this.handleClickOutside);
    },

    computed: {
        popupStyles() {
            if (!this.showPopup || !this.$refs.wrapperRef) {
                return { position: "absolute", zIndex: 9999 };
            }
            const rect = this.$refs.wrapperRef.getBoundingClientRect();
            return {
                position: "absolute",
                left: `${rect.left}px`,
                top: `${rect.bottom + window.scrollY + 4}px`,
                width: `${rect.width}px`,
                zIndex: 9999,
            };
        },
    },

    methods: {
        initializeValue() {
            this.internalValue = this.modelValue
                ? dayjs(this.modelValue).format("YYYY-MM-DD")
                : "";
        },

        openPopup() {
            if (!this.disabled) {
                this.showPopup = true;
                this.isEditing = false;
                this.$refs.dateInput.markAsPopupClick?.();
            }
        },

        closePopup() {
            this.showPopup = false;
        },

        handleClickOutside(e) {
            if (!this.$refs.wrapperRef) return;
            if (this.$refs.wrapperRef.contains(e.target)) return;
            if (
                this.$refs.popupWrapperRef &&
                this.$refs.popupWrapperRef.contains(e.target)
            ) {
                return;
            }
            this.closePopup();
        },

        handleInputFocus() {
            this.isEditing = true;
            this.closePopup();
        },

        handleInputConfirm(val) {
            const parsed = dayjs(val, "YYYY-MM-DD", true);
            if (parsed.isValid()) {
                this.internalValue = parsed.format("YYYY-MM-DD");
                this.$emit("update:modelValue", parsed.toDate());
            } else {
                this.initializeValue();
            }
            this.isEditing = false;
        },

        handleSelectDate(val) {
            const parsed = dayjs(val, "YYYY-MM-DD", true);
            if (!parsed.isValid()) return;

            this.$refs.dateInput.markAsPopupClick?.();

            this.internalValue = parsed.format("YYYY-MM-DD");
            this.$emit("update:modelValue", parsed.toDate());
            this.closePopup();
        },
    },
};
</script>

<template>
    <div class="datepicker-wrapper" ref="wrapperRef">
        <DateInput
            ref="dateInput"
            :modelValue="internalValue"
            :disabled="disabled"
            @focus="handleInputFocus"
            @confirm="handleInputConfirm"
            @click="openPopup"
        />

        <Teleport to="body">
            <div
                v-if="showPopup"
                ref="popupWrapperRef"
                :style="popupStyles"
                @click.stop
            >
                <DatePickerPopup
                    :modelValue="internalValue"
                    @update:internal="handleSelectDate"
                />
            </div>
        </Teleport>
    </div>
</template>

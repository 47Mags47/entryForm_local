<script>
import { default as TimePicker } from "./datePicker/TimePicker.vue";
import { default as CheckBox } from "./CheckBox.vue";
import { default as FormItem } from "../FormItem.vue";

export default {
    components: {
        TimePicker,
        CheckBox,
        FormItem,
    },

    props: {
        modelValue: {
            type: Object,
            required: true,
        },
        name: String,
        disabled: {
            type: Boolean,
            default: false,
        },
    },

    emits: ["update:modelValue"],

    data() {
        return {
            days: [
                { key: "mon", label: "Пн" },
                { key: "tue", label: "Вт" },
                { key: "wed", label: "Ср" },
                { key: "thu", label: "Чт" },
                { key: "fri", label: "Пт" },
                { key: "sat", label: "Сб" },
                { key: "sun", label: "Вс" },
            ],
        };
    },

    methods: {
        updateDay(day, key, value) {
            this.$emit("update:modelValue", {
                ...this.modelValue,
                [day]: {
                    ...this.modelValue[day],
                    [key]: value,
                },
            });
        },
        toggleDay(day, val) {
            const newValue = { ...this.modelValue };

            if (val) {
                if (!newValue[day]) {
                    newValue[day] = {
                        date_start: null,
                        date_end: null,
                        lunch_start: null,
                        lunch_end: null,
                    };
                }
            } else {
                delete newValue[day];
            }

            this.$emit("update:modelValue", newValue);
        },
    },
};
</script>

<template>
    <FormItem name="shedules">
        <div class="work-schedule-time">
            <div v-for="day in days" :key="day.key" class="day-row-container">
                <div class="day-row">
                    <span class="day-label">{{ day.label }}:</span>

                    <CheckBox
                        :modelValue="day.key in modelValue"
                        @update:modelValue="(val) => toggleDay(day.key, val)"
                        :disabled="disabled"
                    />

                    <div v-if="day.key in modelValue" class="time-picker-block">

                        <div class="datepicker-wrapper-relative">
                            <TimePicker
                                mode="time"
                                :modelValue="modelValue[day.key]?.date_start"
                                @update:modelValue="(val) => updateDay(day.key, 'date_start', val)"
                                :name="`${name}[${day.key}][date_start]`"
                                :disabled="disabled"
                                :label="'Начало'"
                            />
                        </div>

                        <div class="datepicker-wrapper-relative">
                            <TimePicker
                                mode="time"
                                :modelValue="modelValue[day.key]?.date_end"
                                @update:modelValue="(val) => updateDay(day.key, 'date_end', val)"
                                :name="`${name}[${day.key}][date_end]`"
                                :disabled="disabled"
                                :label="'Конец'"
                            />
                        </div>

                        <div class="datepicker-wrapper-relative lunch">
                            <TimePicker
                                mode="time"
                                :modelValue="modelValue[day.key]?.lunch_start"
                                @update:modelValue="(val) => updateDay(day.key, 'lunch_start', val)"
                                :name="`${name}[${day.key}][lunch_start]`"
                                :disabled="disabled"
                                :label="'Начало обеда'"
                            />
                        </div>

                        <div class="datepicker-wrapper-relative lunch">
                            <TimePicker
                                mode="time"
                                :modelValue="modelValue[day.key]?.lunch_end"
                                @update:modelValue="(val) => updateDay(day.key, 'lunch_end', val)"
                                :name="`${name}[${day.key}][lunch_end]`"
                                :disabled="disabled"
                                :label="'Конец обеда'"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </FormItem>
</template>

<style lang="sass">
.work
    width: 100%

.work-schedule-time
    display: flex
    flex-direction: column
    gap: 8px

    .day-row-container
        display: flex
        align-items: flex-end
        box-sizing: border-box
        padding: 6px 0

        .day-row
            display: flex
            align-items: flex-end
            width: 100%
            gap: 16px

            .day-label
                width: 30px
                flex-shrink: 0
                font-weight: 500
                text-align: right
                padding-bottom: 4px

            .checkbox
                display: flex
                align-items: flex-end
                justify-content: center
                height: 150px
                margin-bottom: 4px

            .time-picker-block
                display: flex
                align-items: flex-end
                gap: 20px
                flex: 1
                flex-wrap: nowrap

                .datepicker-wrapper-relative
                    position: relative
                    width: 160px
                    flex-shrink: 0
</style>

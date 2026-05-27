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
                { key: "mon", label: "Пн", checked: this.modelValue['mon'] ? true : false },
                { key: "tue", label: "Вт", checked: this.modelValue['tue'] ? true : false },
                { key: "wed", label: "Ср", checked: this.modelValue['wed'] ? true : false },
                { key: "thu", label: "Чт", checked: this.modelValue['thu'] ? true : false },
                { key: "fri", label: "Пт", checked: this.modelValue['fri'] ? true : false },
                { key: "sat", label: "Сб", checked: this.modelValue['sat'] ? true : false },
                { key: "sun", label: "Вс", checked: this.modelValue['sun'] ? true : false },
            ],
            localTime: {}
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
            this.localTime = {
                ...this.modelValue,
                [day]: {
                    ...this.modelValue[day],
                    [key]: value,
                }
            };
        },
        toggleDay(day, val) {
            const newValue = { ...this.modelValue };

            if (val) {
                this.days.forEach(d => {
                    if (d.key === day) {
                        d.checked = true
                    }
                })
                if (!newValue[day]) {
                    newValue[day] = {
                        date_start:     this.localTime[day]?.date_start     ?? '08:00',
                        date_end:       this.localTime[day]?.date_end       ?? '17:00',
                        lunch_start:    this.localTime[day]?.lunch_start    ?? '12:00',
                        lunch_end:      this.localTime[day]?.lunch_end      ?? '13:00',
                    };
                }
            } else {
                this.days.forEach(d => {
                    if (d.key === day) {
                        d.checked = false
                    }
                })

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
                        :modelValue="day.checked"
                        @update:modelValue="(val) => toggleDay(day.key, val)"
                        :disabled="disabled"
                    />

                    <div :class="{ 'active': day.checked }"  class="time-picker-block">

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

                    <div class="day-status" :class="{ 'active': !day.checked }">
                        <span> не назначено </span>
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
            position: relative
            display: flex
            align-items: flex-end
            width: 100%
            gap: 16px
            height: 50px

            .day-status
                position: absolute
                transition: .5s ease
                right: 35%
                &.active
                    opacity: 1
                    transform: translateX(-20px)
                    pointer-events: auto
                &:not(.active)
                    opacity: 0
                    transform: translateX(0px)
                    pointer-events: none;

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
                transition: .5s ease

                &.active
                    opacity: 1
                    transform: translateX(0px)
                    pointer-events: auto

                &:not(.active)
                    opacity: 0
                    transform: translateX(-10px)
                    pointer-events: none

                .datepicker-wrapper-relative
                    position: relative
                    width: 160px
                    flex-shrink: 0

</style>

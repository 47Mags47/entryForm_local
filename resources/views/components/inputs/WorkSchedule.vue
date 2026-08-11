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
                { key: "mon", label: "Пн", workTimeChecked: this.modelValue['mon']?.date_start && this.modelValue['mon']?.date_end ? true : false, lunchTimeChecked: this.modelValue['mon']?.lunch_start && this.modelValue['mon']?.lunch_end ? true : false },
                { key: "tue", label: "Вт", workTimeChecked: this.modelValue['tue']?.date_start && this.modelValue['tue']?.date_end ? true : false, lunchTimeChecked: this.modelValue['tue']?.lunch_start && this.modelValue['tue']?.lunch_end ? true : false },
                { key: "wed", label: "Ср", workTimeChecked: this.modelValue['wed']?.date_start && this.modelValue['wed']?.date_end ? true : false, lunchTimeChecked: this.modelValue['wed']?.lunch_start && this.modelValue['wed']?.lunch_end ? true : false },
                { key: "thu", label: "Чт", workTimeChecked: this.modelValue['thu']?.date_start && this.modelValue['thu']?.date_end ? true : false, lunchTimeChecked: this.modelValue['thu']?.lunch_start && this.modelValue['thu']?.lunch_end ? true : false },
                { key: "fri", label: "Пт", workTimeChecked: this.modelValue['fri']?.date_start && this.modelValue['fri']?.date_end ? true : false, lunchTimeChecked: this.modelValue['fri']?.lunch_start && this.modelValue['fri']?.lunch_end ? true : false },
                { key: "sat", label: "Сб", workTimeChecked: this.modelValue['sat']?.date_start && this.modelValue['sat']?.date_end ? true : false, lunchTimeChecked: this.modelValue['sat']?.lunch_start && this.modelValue['sat']?.lunch_end ? true : false },
                { key: "sun", label: "Вс", workTimeChecked: this.modelValue['sun']?.date_start && this.modelValue['sun']?.date_end ? true : false, lunchTimeChecked: this.modelValue['sun']?.lunch_start && this.modelValue['sun']?.lunch_end ? true : false },
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
        toggleWorkTime(day, val) {
            const newValue = { ...this.modelValue };

            if (val) {
                this.days.forEach(d => {
                    if (d.key === day) {
                        d.workTimeChecked = true
                        d.lunchTimeChecked = true
                    }
                })

                newValue[day] = {
                    date_start:     this.localTime[day]?.date_start  ?? '08:00',
                    date_end:       this.localTime[day]?.date_end    ?? '17:00',
                    lunch_start:    this.localTime[day]?.lunch_start ?? '12:00',
                    lunch_end:      this.localTime[day]?.lunch_end   ?? '13:00',
                };
            } else {
                this.days.forEach(d => {
                    if (d.key === day) {
                        d.workTimeChecked = false
                        d.lunchTimeChecked = true
                    }
                })

                if(newValue[day])
                    delete newValue[day]
            }

            this.$emit("update:modelValue", newValue);
        },

        toggleLunchTime(day, val) {
            if (!this.days.find(item => item.key === day)?.workTimeChecked)
                return

            const newValue = { ...this.modelValue };

            // если чекбокс тру
            if (val) {
                this.days.forEach(d => {
                    if (d.key === day)
                        d.lunchTimeChecked = true
                })

                newValue[day] = {
                    date_start:     this.localTime[day]?.date_start  ?? '08:00',
                    date_end:       this.localTime[day]?.date_end    ?? '17:00',
                    lunch_start:    this.localTime[day]?.lunch_start ?? '12:00',
                    lunch_end:      this.localTime[day]?.lunch_end   ?? '13:00',
                };
            } else {
                this.days.forEach(d => {
                    if (d.key === day)
                        d.lunchTimeChecked = false
                })

                if(newValue[day]) {
                    newValue[day].lunch_start = null
                    newValue[day].lunch_end = null
                }
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

                    <div class="time-picker-container">
                        <CheckBox
                            :modelValue="day.workTimeChecked"
                            @update:modelValue="(val) => toggleWorkTime(day.key, val)"
                            :disabled="disabled"
                        />

                        <div :class="{ 'active': day.workTimeChecked }"  class="time-picker-block">
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
                        </div>

                        <div class="time-status" :class="{ 'active': !day.workTimeChecked }">
                            <span> не назначено </span>
                        </div>
                    </div>

                    <div class="time-picker-container">
                        <CheckBox
                            :modelValue="day.lunchTimeChecked && day.workTimeChecked"
                            @update:modelValue="(val) => toggleLunchTime(day.key, val)"
                            :disabled="disabled"
                        />

                        <div :class="{ 'active': day.lunchTimeChecked && day.workTimeChecked }"  class="time-picker-block">
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

                        <div class="time-status" :class="{ 'active': !day.lunchTimeChecked || !day.workTimeChecked }">
                            <span> не назначено </span>
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
            position: relative
            display: flex
            align-items: flex-end
            width: 100%
            gap: 16px
            height: 50px

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

            .time-picker-container
                position: relative
                display: flex
                gap: 10px
                align-items: flex-end

                .time-status
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
                        pointer-events: none

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

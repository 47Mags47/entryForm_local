<script>
import { router } from "@inertiajs/vue3";
import { default as DatePicker } from "../inputs/datePicker/DatePicker.vue";
import { default as BlueButton } from "../buttons/BlueButton.vue";
import { default as ChevronRightIco } from "../icons/ChevronRightIco.vue";
import { default as ChevronLeftIco } from "../icons/ChevronLeftIco.vue";
import dayjs from "dayjs";

export default {
    components: {
        ChevronRightIco,
        ChevronLeftIco,
        DatePicker,
        BlueButton,
    },

    props: {
        header: String,
        division_id: Number,
        dateProp: Object,
    },

    data() {
        const params = new URLSearchParams(window.location.search);

        const day = params.get('day');
        const month = params.get('month');
        const year = params.get('year');

        let currentDate = null
        if (day && month && year) {
            currentDate = dayjs()
                .year(year)
                .month(month - 1)
                .date(day);
        }

        const previousDay = dayjs()
            .year(this.dateProp.previous.year)
            .month(this.dateProp.previous.month - 1)
            .date(this.dateProp.previous.day);

        const nextDay = dayjs()
            .year(this.dateProp.next.year)
            .month(this.dateProp.next.month - 1)
            .date(this.dateProp.next.day);

        const todayDate = dayjs();

        return {
            currentDate,
            previousDay,
            nextDay,
            todayDate,
        };
    },

    methods: {
        goToDate(date) {
            router.get(route("events.index", { division: this.division_id }), {
                year: date.year(),
                month: date.month() + 1,
                day: date.date(),
            });
        },
        goToPreviousDay() {
            this.goToDate(this.previousDay);
        },
        goToToday() {
            this.goToDate(this.todayDate);
        },
        goToNextDay() {
            this.goToDate(this.nextDay);
        },
        handleDateChange(val) {
            const date = dayjs(val);
            if (date.isValid()) {
                this.goToDate(date);
            }
        },
    },
};
</script>

<template>
    <div class="timeline-header">
        <DatePicker
            name="start_date"
            :value="currentDate?.format('YYYY-MM-DD')"
            @update:value="handleDateChange"
        />

        <div class="header-title">
            {{ header }}
        </div>

        <div class="date-nav-buttons">
            <BlueButton class="nav-btn" @click="goToPreviousDay">
                <ChevronLeftIco />
            </BlueButton>

            <BlueButton class="nav-btn" @click="goToToday">
                Сегодня
            </BlueButton>

            <BlueButton class="nav-btn" @click="goToNextDay">
                <ChevronRightIco />
            </BlueButton>
        </div>
    </div>
</template>

<style lang="sass" scoped>
.timeline-header
        background: rgb(216, 216, 255)
        display: flex
        justify-content: space-between
        align-items: center
        padding: 6px 12px
        gap: 8px
        position: sticky
        top: 0

        .header-title
            flex: 1
            text-align: center
            font-size: 16px
            font-weight: 500

        .date-nav-buttons
            display: flex
            gap: 6px
            white-space: nowrap

</style>
<style lang="sass" scoped>
.datepicker-input
    width: 280px
</style>

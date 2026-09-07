<script>
import { usePage, router } from "@inertiajs/vue3";
import { default as DatePicker } from "../inputs/datePicker/DatePicker.vue";
import { default as BlueButton } from "../buttons/BlueButton.vue";
import { default as ChevronRightIco } from "../icons/ChevronRightIco.vue";
import { default as ChevronLeftIco } from "../icons/ChevronLeftIco.vue";
import { DateTime, Interval } from "luxon";

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
        return {};
    },

    computed: {
        dates: () => usePage().props.dates,
        division: () => usePage().props.division,
        currentDate() {
            return DateTime.now().set({
                year: this.dates.current.year,
                month: this.dates.current.month,
                day: this.dates.current.day,
            });
        },
        interval() {
            const start = DateTime.fromFormat(
                this.division.data.shedules.mon.date_start,
                "HH:mm",
            ).set({
                year: this.dates.current.year,
                month: this.dates.current.month,
                day: this.dates.current.day,
            });

            const end = DateTime.fromFormat(
                this.division.data.shedules.mon.date_end,
                "HH:mm",
            ).set({
                year: this.dates.current.year,
                month: this.dates.current.month,
                day: this.dates.current.day,
            });
            return Interval.fromDateTimes(start, end);
        },
    },

    methods: {
        handleDateChange(date) {
            const luxonDate = DateTime.fromISO(date);
            const isValidDate = luxonDate.isValid;

            if (isValidDate) {
                this.goToDate(luxonDate);
            }
        },

        goToDate(date) {
            router.get(
                route("events.index", { division: this.division.data.id }),
                {
                    year: date.year,
                    month: date.month,
                    day: date.day,
                },
            );
        },
        goToPreviousDay() {
            this.goToDate(this.dates.previous);
        },
        goToToday() {
            const today = DateTime.now()
            this.goToDate(today);
        },
        goToNextDay() {
            this.goToDate(this.dates.next);
        },
    },
};
</script>

<template>
    <div class="timeline-header">
        <DatePicker
            name="start_date"
            :value="currentDate?.toFormat('yyyy-MM-dd')"
            @update:value="handleDateChange"
        />

        <div class="header-title">Календарь</div>

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
thead
    min-width: 200px
.timeline-header
    position: relative
    background: rgb(216, 216, 255)
    display: flex
    justify-content: space-between
    align-items: center
    padding: 6px 12px
    gap: 8px
    z-index: 1000
    border-radius: 14px 14px 0px 0px

    .datepicker-input
        width: 280px

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

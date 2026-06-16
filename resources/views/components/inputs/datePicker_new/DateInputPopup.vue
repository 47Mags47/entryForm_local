<script>
import { DateTime, Interval } from "luxon";
import Select from '../select/Select.vue';
import ChevronLeftIco from '../../icons/ChevronLeftIco.vue'
import ChevronRightIco from '../../icons/ChevronRightIco.vue'
import BlueButton from "../../buttons/BlueButton.vue";

export default {
    props: {
        checkValid: {
            type: Function,
            default: (day) => true
        },
        onClick: {
            type: Function,
            default: () => {}
        },
        selectedDate: {
            type: String,
            default: null,
            validator(val){
                return /[0-9]{4}-[0-9]{2}-[0-9]{2}/.test(val)
            }
        },
        startInterval: {
            type: [Object, String],
            default: null
        },
        endInterval: {
            type: [Object, String],
            default: null
        },
        test: {
            type: Object,
            default: {}
        }
    },
    components: {
        Select,
        ChevronLeftIco,
        ChevronRightIco,
        BlueButton
    },
    computed: {
        now: () => DateTime.now(),

        startOfMonth: function() {
            return this.currentDate.startOf('month')
        },
        startOfWeek: function() {
            return this.startOfMonth.startOf('week')
        },
        endOfMonth: function() {
            return this.currentDate.endOf('month')
        },
        endOfWeek: function() {
            return this.endOfMonth.endOf('week')
        },
        interval() {
            let interval = Interval.fromDateTimes(this.startOfWeek, this.endOfWeek)

            if (interval.splitBy({ week: 1 }).length === 4)
                interval = interval.set({ start: interval.start.minus({ weeks: 1 }) });

            if (interval.splitBy({ week: 1 }).length === 5)
                interval = interval.set({ end: interval.end.plus({ weeks: 1 }) });

            return interval
        },
        monthList(){
            return [
                { label: 'Январь',      value: 1    },
                { label: 'Февраль',     value: 2    },
                { label: 'Март',        value: 3    },
                { label: 'Апрель',      value: 4    },
                { label: 'Май',         value: 5    },
                { label: 'Июнь',        value: 6    },
                { label: 'Июль',        value: 7    },
                { label: 'Август',      value: 8    },
                { label: 'Сентябрь',    value: 9    },
                { label: 'Октябрь',     value: 10   },
                { label: 'Ноябрь',      value: 11   },
                { label: 'Декабрь',     value: 12   },
            ]
        },
        availableInterval(){
            let startInterval = this.startInterval ?? this.now.startOf('year').minus({'year': 5})
            let endInterval = this.endInterval ?? this.now.endOf('year').plus({'year': 5})
            return Interval.fromDateTimes(startInterval, endInterval)
        },
        yearList(){
            let startInterval = this.currentDate.startOf('year').minus({'year': 5})
            let endInterval = this.currentDate.endOf('year').plus({'year': 5})

            return Interval.fromDateTimes(startInterval, endInterval).splitBy({ year: 1 }).map((interval) => ({
                label: interval.start.toFormat('yyyy'),
                value: interval.start.toFormat('yyyy'),
            }))
        },

        initialDate(){
            if(this.selectedDate !== null)
                return DateTime.fromFormat(this.selectedDate, 'yyyy-MM-dd')

            if(this.startInterval !== null)
                if(typeof this.startInterval === 'string')
                    return DateTime.fromFormat(this.startInterval, 'yyyy-MM-dd')
                else if(typeof this.startInterval === 'object')
                    return this.startInterval

            return DateTime.now()
        }
    },
    data() {
        let inintialDate = DateTime.now()
        if(this.selectedDate !== null)
            inintialDate = DateTime.fromFormat(this.selectedDate, 'yyyy-MM-dd')

        if(this.startInterval !== null)
            if(typeof this.startInterval === 'string')
                inintialDate = DateTime.fromFormat(this.startInterval, 'yyyy-MM-dd')
            else if(typeof this.startInterval === 'object')
                inintialDate = this.startInterval


        return {
            currentDate: inintialDate
        }
    },
    methods: {
        goToNextMonth(){
            this.currentDate = this.currentDate.plus({ month: 1 });
        },

        goToPrevMonth(){
            this.currentDate = this.currentDate.minus({ month: 1 });
        },

        checkDateInMonth(date){
            return Interval.fromDateTimes(this.startOfMonth, this.endOfMonth).contains(date)
        },

        dayClickHandler(date){
            this.onClick(date)
        },

        setMonthHandler(option){
            let nextmonth = this.currentDate.set({'month': option.value})

            if(this.checkMonthAvaible(nextmonth))
                this.currentDate = this.currentDate.set({'month': option.value})
        },

        setYearHandler(option){
            this.currentDate = this.currentDate.set({'year': option.value})
        },

        checkMonthAvaible(month){
            if(this.startInterval !== null && month < this.startInterval)
                return false

            if(this.endInterval !== null && month > this.endInterval)
                return false

            return true
        },

        checkSelectable(date){
            if(this.startInterval !== null && date < this.startInterval)
                return false

            if(this.endInterval !== null && date > this.endInterval)
                return false

            return true
        }
    },
    watch: {
        selectedDate: {
            handler(newv){
                this.currentDate = DateTime.fromFormat(newv, 'yyyy-MM-dd')
            }
        }
    },
}
</script>

<template>
    <div class="date-input-popup-container">
        <div class="header-container">
            <BlueButton class="year-button" :onClick="goToPrevMonth" :disabled="!checkMonthAvaible(currentDate.minus({'month': 1}))">
                <ChevronLeftIco />
            </BlueButton>
            <div class="current-date-container">
                <Select
                    class="month-select"
                    :has-search="false"
                    :modelValue="currentDate.toFormat('M')"
                    :options="monthList"
                    :onSelect="setMonthHandler"
                />

                <Select
                    class="year-select"
                    :has-search="false"
                    :modelValue="currentDate.toFormat('yyyy')"
                    :options="yearList"
                    :onSelect="setYearHandler"
                />
            </div>
            <BlueButton class="year-button" :onClick="goToNextMonth" :disabled="!checkMonthAvaible(currentDate.plus({'month': 1}))">
                <ChevronRightIco />
            </BlueButton>
        </div>
        <div class="content-container">
            <table>
                <thead>
                    <tr>
                        <th>ПН</th>
                        <th>ВТ</th>
                        <th>СР</th>
                        <th>ЧТ</th>
                        <th>ПТ</th>
                        <th>СБ</th>
                        <th>ВС</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="weekInterval in interval.splitBy({ week: 1 })" >
                        <td v-for="dayInterval in weekInterval.splitBy({ day: 1 })">
                            <div
                                v-if="checkValid(dayInterval.start) && checkDateInMonth(dayInterval.start) && checkSelectable(dayInterval.start)"
                                class="day-cell-container available"
                                :class="{'current-day': dayInterval.start.toMillis() == now.startOf('day').toMillis()}"
                                @click="() => dayClickHandler(dayInterval.start)"
                            >
                                {{ dayInterval.start.day }}
                            </div>
                            <div
                                v-else
                                class="day-cell-container"
                                :class="{
                                    'disabled': !checkValid(dayInterval.start) || !checkSelectable(dayInterval.start),
                                    'other-month': !checkDateInMonth(dayInterval.start)
                                }"
                            >
                                {{ dayInterval.start.day }}
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style lang="sass" scoped>
.date-input-popup-container
    position: absolute

    display: flex
    flex-direction: column

    background: white

    .header-container
        width: 100%
        height: 40px
        padding: 5px 7px

        display: flex
        justify-content: space-between
        align-items: center
        gap: 10px

        .current-date-container
            flex: 1
            display: flex
            gap: 10px
            .month-select
                flex: 1
            .year-select
                width: 85px
    .content-container
        padding: 5px
        table
            width: 100%
            height: 100%
            td
                width: 45px
                height: 45px
            .day-cell-container
                width: 100%
                height: 100%
                padding: 5px

                display: flex
                justify-content: center
                align-items: center

                background: white
                border-radius: 50%
                overflow: hidden

                color: #333
                &.current-day
                    color: var(--blue-button-background-color)
                &.available
                    cursor: pointer
                    &:hover
                        background: #eee
                &.disabled, &.other-month
                    cursor: not-allowed
                    color: #ddd
</style>

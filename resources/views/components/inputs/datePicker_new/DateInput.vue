<script>
import { DateTime } from "luxon";
import CalendarIco from "../../icons/CalendarIco.vue";
import DateInputPopup from './DateInputPopup.vue'

export default {
    components: {
        CalendarIco,
        DateInputPopup
    },
    props: {
        // Input
        name: {
            type: String,
            default: null
        },
        value: {
            type: [Object, String]
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        placeholder: {
            type: String,
            default: "ДД.MM.ГГГГ",
        },

        // Functions
        checkValid: {
            type: Function,
            default: (day) => true
        },
        startInterval: {
            type: [Object, String],
            default: null
        },
        endInterval: {
            type: [Object, String],
            default: null
        },

        // Handlers
        onUpdate: {
            type: Function,
            default: () => {}
        },
    },
    data() {
        return {
            isPopupOpen: false,
            selectedDate: this.value !== null ? this.value : null,

            popupStyle: {
                bottom: null,
                position: 'absolute',
            },
        };
    },
    methods: {
        popupButtonClickHandler() {
            this.isPopupOpen = !this.isPopupOpen

            this.isPopupOpen ? this.fixPopupBottomPosition() : null
        },

        async fixPopupBottomPosition() {
            await this.$nextTick()

            const popupRect = this.$refs.dateInputPopup.$el.getBoundingClientRect()
            const vh = window.innerHeight

            if (popupRect.bottom > vh) {
                this.popupStyle.position = 'fixed'
                this.popupStyle.bottom = '10px'
            }
        },

        dayClickHandler(date) {
            this.selectedDate = date
            this.onUpdate(this.selectedDate.toFormat('yyyy-MM-dd'))
            this.isPopupOpen = false
        },

        inputBlurHandler(e) {
            let value = e.target.value
            let luxonDate = DateTime.fromFormat(value, 'yyyy-MM-dd')

            if(this.startInterval !== null && luxonDate < this.startInterval && /[0-9]{4}-[0-9]{2}-[0-9]{2}/.test(value)){
                alert('Дата выходит за возможный диапазон')
                this.$refs.inputRef.value = this.selectedDate?.toFormat('yyyy-MM-dd')
                return
            }

            if(this.endInterval !== null && luxonDate > this.endInterval && /[0-9]{4}-[0-9]{2}-[0-9]{2}/.test(value)){
                alert('Дата выходит за возможный диапазон')
                this.$refs.inputRef.value = this.selectedDate.toFormat('yyyy-MM-dd')
                return
            }

            if(/[0-9]{4}-[0-9]{2}-[0-9]{2}/.test(value)){
                this.selectedDate = DateTime.fromFormat(value, 'yyyy-MM-dd')
                this.onUpdate(value)
            }
        },

        outsideClickHandler(e) {
            if (!this.$refs.wrapper.contains(e.target)){
                this.isPopupOpen = false
            };
        },
    },

    mounted(){
        document.addEventListener("mousedown", this.outsideClickHandler)
    },
    unmounted(){
        document.removeEventListener("mousedown", this.outsideClickHandler)
    },
}
</script>

<template>
    <div class="date-input-wrapper" ref="wrapper">
        <input
            type="date"
            class="custom-date-input"
            ref="inputRef"
            :name
            :disabled
            :placeholder
            :value="selectedDate !== null ? selectedDate.toFormat('yyyy-MM-dd') : value"
            @blur="inputBlurHandler"
        />
        <div class="calendar-icon">
            <CalendarIco @click="popupButtonClickHandler" />
        </div>
        <DateInputPopup v-show="isPopupOpen"
            ref="dateInputPopup"
            :style="popupStyle"
            :checkValid
            :startInterval
            :endInterval
            :onClick="dayClickHandler"
            :selectedDate="selectedDate?.toFormat('yyyy-MM-dd') ?? null"
        />
    </div>
</template>

<style lang="sass">
.date-input-wrapper
    position: relative
    display: inline-block
    width: 100%

    .custom-date-input
        @include input()
        width: 100%

        &::-webkit-calendar-picker-indicator
            display: none
            -webkit-appearance: none

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
</style>

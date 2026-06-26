<script>
import { DateTime } from "luxon";
import CalendarIco from "../../icons/CalendarIco.vue";
import DateInputPopup from './DateInputPopup.vue'
import { Teleport } from "vue";

export default {
    components: {
        CalendarIco,
        DateInputPopup
    },
    props: {
        isRange: {
            type: Boolean,
            default: false,
        },
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
        onFromUpdate: {
            type: Function,
            default: () => {}
        },
        onToUpdate: {
            type: Function,
            default: () => {}
        }
    },
    data() {
        return {
            isPopupOpen:        false,

            isFirstClick:       true,
            // HACK добавить валидацию формата даты
            selectedDateFrom:   this.value?.from ? this.value.from : null,
            selectedDateTo:     this.value?.to   ? this.value.to   : null,
            selectedDate:       null,

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

        //  Пикер
        dayClickHandler(date) {
            this.selectedDate = date

            if (this.isFirstClick) {
                this.selectedDateFrom = date
                this.selectedDateTo   = null
            }
            else
                this.selectedDateTo   = date

            const dateNotNull = this.selectedDateFrom?.isValid && this.selectedDateTo?.isValid

            if (dateNotNull && this.selectedDateFrom > this.selectedDateTo)
                [this.selectedDateFrom, this.selectedDateTo] = [this.selectedDateTo, this.selectedDateFrom]

            this.onFromUpdate(this.selectedDateFrom)
            this.onToUpdate(this.selectedDateTo)

            this.isFirstClick = !this.isFirstClick
        },

        inputFromBlurHandler(e) {
            let value = e.target.value
            let luxonDate = DateTime.fromFormat(value, 'yyyy-MM-dd')

            if(this.startInterval !== null && luxonDate < this.startInterval && /[0-9]{4}-[0-9]{2}-[0-9]{2}/.test(value)){
                alert('Дата выходит за возможный диапазон')
                this.$refs.inputFromRef.value = this.selectedDateFrom?.toFormat('yyyy-MM-dd')
                return
            }

            if(this.endInterval !== null && luxonDate > this.endInterval && /[0-9]{4}-[0-9]{2}-[0-9]{2}/.test(value)){
                alert('Дата выходит за возможный диапазон')
                this.$refs.inputFromRef.value = this.selectedDateFrom.toFormat('yyyy-MM-dd')
                return
            }

            if(/[0-9]{4}-[0-9]{2}-[0-9]{2}/.test(value)){
                this.selectedDateFrom = DateTime.fromFormat(value, 'yyyy-MM-dd')
                this.onFromUpdate(value)
            }
        },
        inputToBlurHandler(e) {
            let value = e.target.value
            let luxonDate = DateTime.fromFormat(value, 'yyyy-MM-dd')

            if(this.startInterval !== null && luxonDate < this.startInterval && /[0-9]{4}-[0-9]{2}-[0-9]{2}/.test(value)){
                alert('Дата выходит за возможный диапазон')
                this.$refs.inputToRef.value = this.selectedDateTo?.toFormat('yyyy-MM-dd')
                return
            }

            if(this.endInterval !== null && luxonDate > this.endInterval && /[0-9]{4}-[0-9]{2}-[0-9]{2}/.test(value)){
                alert('Дата выходит за возможный диапазон')
                this.$refs.inputToRef.value = this.selectedDateTo.toFormat('yyyy-MM-dd')
                return
            }

            if(/[0-9]{4}-[0-9]{2}-[0-9]{2}/.test(value)){
                this.selectedDateTo = DateTime.fromFormat(value, 'yyyy-MM-dd')
                this.onToUpdate(value)
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
            ref="inputFromRef"
            :name="`${name}_from`"
            :disabled
            :placeholder
            :value="selectedDateFrom !== null ? selectedDateFrom.toFormat('yyyy-MM-dd') : null"
            @blur="inputFromBlurHandler"
        />
        <div class="overlay-calendar-from"></div>
        <input
            type="date"
            class="custom-date-input"
            ref="inputToRef"
            :name="`${name}_to`"
            :disabled
            :placeholder
            :value="selectedDateTo !== null ? selectedDateTo.toFormat('yyyy-MM-dd') : null"
            @blur="inputToBlurHandler"
        />
        <div class="overlay-calendar-to"></div>
        <div class="calendar-icon">
            <CalendarIco @click="popupButtonClickHandler" />
        </div>
        <DateInputPopup v-show="isPopupOpen"
            ref="dateInputPopup"
            :isRange
            :style="popupStyle"
            :checkValid
            :startInterval
            :endInterval
            :onClick="dayClickHandler"
            :selectedDate="selectedDate?.toFormat('yyyy-MM-dd') ?? null"
            :selectedDateBetween="{
                from: selectedDateFrom?.toFormat('yyyy-MM-dd'),
                to:   selectedDateTo?.toFormat('yyyy-MM-dd')
            }"
        />
    </div>
</template>

<style lang="sass">
.date-input-wrapper
    @include input()

    position: relative
    display: flex
    width: 100%
    padding: 0

    .overlay-calendar-from
        position: absolute
        left: 110px
        top: 50%
        transform: translateY(-50%)

        width: 25px
        height: 25px
        background: white

    .overlay-calendar-to
        position: absolute
        right: 10px
        top: 50%
        transform: translateY(-50%)

        width: 25px
        height: 25px
        background: white

    .custom-date-input
        border: 0
        min-width: 150px
        width: 100%

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

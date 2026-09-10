<script>
import { DivisionTab } from "@includes";
import { usePage } from "@inertiajs/vue3";
import { DateTime, Interval } from "luxon";
import {
    DatePicker,
    BlueButton,
    ArrowLeftIco,
    ArrowRightIco,
} from "@components";
import TimeLineHeader from "../../components/calendar/TimeLineHeader.vue";
import TimeLineTbody from "../../components/calendar/TimeLineTbody.vue";

export default {
    components: {
        DivisionTab,
        DatePicker,
        BlueButton,
        ArrowLeftIco,
        ArrowRightIco,
        TimeLineHeader,
        TimeLineTbody,
    },

    data() {
        return {
            scrollLeft: 0,
            maxScroll: 0,
            isDragging: false,
            startX: 0,
            startScrollLeft: 0,

            // Скролл колесом
            wheelTarget: 0,
            wheelAnimation: null,
        };
    },

    computed: {
        dates: () => usePage().props.dates,
        division: () => usePage().props.division,
        subscribes: () => usePage().props.subscribes,
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

        canScrollLeft() {
            return this.scrollLeft > 5;
        },

        canScrollRight() {
            // При первой загрузке maxScroll ещё 0,
            // но стрелка вправо должна быть видна
            if (this.maxScroll === 0) {
                return true;
            }

            return this.scrollLeft < this.maxScroll - 5;
        },
    },

    methods: {
        updateScrollPosition(event) {
            const wrapper = event.target;

            this.maxScroll = wrapper.scrollWidth - wrapper.clientWidth;
            this.scrollLeft = wrapper.scrollLeft;
        },
        // СКРОЛЛ
        smoothScrollTo(target) {
            const wrapper = this.$refs.tableWrapper;

            if (!wrapper) return;

            const maxScroll = wrapper.scrollWidth - wrapper.clientWidth;

            this.wheelTarget = Math.max(
                0,
                Math.min(target, maxScroll)
            );

            if (this.wheelAnimation) {
                return;
            }

            const animate = () => {
                const current = wrapper.scrollLeft;
                const diff = this.wheelTarget - current;

                if (Math.abs(diff) < 0.5) {
                    wrapper.scrollLeft = this.wheelTarget;
                    this.wheelAnimation = null;
                    return;
                }

                wrapper.scrollLeft += diff * 0.2;

                this.wheelAnimation = requestAnimationFrame(animate);
            };

            this.wheelAnimation = requestAnimationFrame(animate);
        },

        scrollTable(direction) {
            const wrapper = this.$refs.tableWrapper;

            if (!wrapper) return;

            const maxScroll = wrapper.scrollWidth - wrapper.clientWidth;
            const amount = maxScroll / 2;

            // Если уже идёт анимация — продолжаем от её текущей цели
            const currentTarget = this.wheelAnimation
                ? this.wheelTarget
                : wrapper.scrollLeft;

            const target =
                direction === "right"
                    ? currentTarget + amount
                    : currentTarget - amount;

            this.smoothScrollTo(target);
        },

        horizontalScroll(event) {
            const wrapper = this.$refs.tableWrapper;

            if (!wrapper) return;

            if (wrapper.scrollWidth <= wrapper.clientWidth) {
                return;
            }

            event.preventDefault();

            const currentTarget = this.wheelAnimation
                ? this.wheelTarget
                : wrapper.scrollLeft;

            this.smoothScrollTo(
                currentTarget + event.deltaY
            );
        },

        // СКРОЛЛ ЗАЖАТИЕМ КНОПКОЙ МЫШИ
        startDrag(event) {
            const wrapper = this.$refs.tableWrapper;

            if (!wrapper) {
                return;
            }

            // Останавливаем анимацию колесика
            if (this.wheelAnimation) {
                cancelAnimationFrame(this.wheelAnimation);
                this.wheelAnimation = null;
            }

            // Синхронизируем цель с фактической позицией
            this.wheelTarget = wrapper.scrollLeft;

            this.isDragging = true;
            this.startX = event.pageX;
            this.startScrollLeft = wrapper.scrollLeft;

            wrapper.classList.add("dragging");
        },
        drag(event) {
            if (!this.isDragging) return;

            const wrapper = this.$refs.tableWrapper;
            const x = event.pageX;
            const walk = x - this.startX;

            wrapper.scrollLeft = this.startScrollLeft - walk;
        },
        stopDrag() {
            if (!this.isDragging) return;

            this.isDragging = false;

            const wrapper = this.$refs.tableWrapper;

            if (wrapper) {
                wrapper.classList.remove("dragging");

                this.wheelTarget = wrapper.scrollLeft;
                this.scrollLeft = wrapper.scrollLeft;
            }
        },
    },
};
</script>

<template>
    <DivisionTab current="event-calendar">
        <div class="timeline-wrapper">
            <TimeLineHeader />

            <div class="timeline-table-wrapper">
                <div
                    class="table-wrapper"
                    ref="tableWrapper"
                    @scroll="updateScrollPosition"
                    @wheel="horizontalScroll"
                    @mousedown="startDrag"
                    @mousemove="drag"
                    @mouseup="stopDrag"
                    @mouseleave="stopDrag"
                >
                    <table>
                        <thead>
                            <tr>
                                <th>
                                </th>
                                <th
                                    v-for="section in interval.splitBy({
                                        minutes: 30,
                                    })"
                                >
                                    {{ section.start.toFormat("HH:mm") }}
                                </th>
                            </tr>
                        </thead>
                        <TimeLineTbody
                            :division="division"
                            :subscribes="subscribes"
                        />
                    </table>
                </div>

                <div v-if="subscribes.length !== 0" class="button-move-scroll-container">
                    <BlueButton
                        type="button"
                        @click="scrollTable('left')"
                        :class="{ 'button-hidden': !canScrollLeft }"
                    >
                        <ArrowLeftIco />
                    </BlueButton>

                    <BlueButton
                        type="button"
                        @click="scrollTable('right')"
                        :class="{ 'button-hidden': !canScrollRight }"
                    >
                        <ArrowRightIco />
                    </BlueButton>
                </div>
            </div>
        </div>
    </DivisionTab>
</template>

<style lang="sass" scoped>
.timeline-wrapper
    margin: 24px
    border: 1px solid #88a2ff81
    border-radius: 14px 14px 0px 0px

    .timeline-table-wrapper
        position: relative
        .table-wrapper
            @include scroll()
            overflow-x: auto
            overflow-y: hidden

            &.dragging
                cursor: grabbing
                user-select: none

        .button-move-scroll-container
            display: flex
            justify-content: space-between
            align-items: center
            padding: 5px 20px
            padding-left: 160px
            margin-bottom: 10px

            position: absolute
            width: 100%
            height: 100%

            right: 0
            bottom: 0

            z-index: 100

            pointer-events: none

            button
                pointer-events: auto
                height: 25px
                background: #63beff

            .button-hidden
                opacity: 0
                pointer-events: none

        // TABLE
        table
            position: relative
            width: 100%
            border-collapse: separate
            border-spacing: 0

            th
                border: 1px solid #88a2ff81
                padding: 6px
                vertical-align: top
                word-break: break-word

            th:first-child
                position: sticky
                left: 0
                z-index: 110
                width: 140px
                border: 1px solid #88a2ff81
                // outline: 1px solid #88a2ff81
                background: rgb(216, 216, 255)

            :deep(tbody)
                position: relative
                td
                    border: 1px solid #88a2ff81
                    padding: 6px
                    height: 90px
                    vertical-align: top
                    word-break: break-word

                td:first-child
                    position: sticky
                    left: 0
                    z-index: 101
                    width: 140px
                    border: 1px solid #88a2ff81
                    // outline: 1px solid #88a2ff81
                    background: rgb(216, 216, 255)

:deep(.date-input-wrapper)
    width: 280px

// EVENT BLOCK
.event-block
    position: absolute
    top: 10%
    height: 80%
    background: linear-gradient(135deg, #ab9dff, #88a2ff)
    border-radius: 4px
    padding: 4px 6px
    color: #fff
    font-size: 12px
    font-weight: 500
    white-space: nowrap
    overflow: hidden
    text-overflow: ellipsis
    cursor: pointer
    z-index: 10
</style>

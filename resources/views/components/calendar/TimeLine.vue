<script>
import { router, usePage } from "@inertiajs/vue3";
import TimeLineHeader from "./TimeLineHeader.vue";
import TimeLineThead from "./TimeLineThead.vue";
import TimeLineTbody from "./TimeLineTbody.vue";
import { ArrowLeftIco, ArrowRightIco } from "../icons/index.js";
import BlueButton from "../buttons/BlueButton.vue";

export default {
    components: {
        TimeLineHeader,
        TimeLineThead,
        TimeLineTbody,
        ArrowLeftIco, ArrowRightIco,
        BlueButton
    },

    props: {
        header: String,
    },

    data() {
        const division = usePage().props.current_division.data;
        const subscribes = usePage().props.subscribes;
        const dateProp = usePage().props.dates;

        return {
            dateProp,
            division,
            subscribes,
            scrollLeft: 0,
            maxScroll: 0,
            isDragging: false,
            startX: 0,
            startScrollLeft: 0,
        };
    },

    computed: {
        allSlots() {
            if (!this.subscribes?.length) return [];
            const slots = new Set();
            this.subscribes.forEach((r) => {
                if (r.timeline) {
                    Object.keys(r.timeline).forEach((s) => slots.add(s));
                }
            });
            return [...slots].sort();
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
        show(routeName, params) {
            router.get(route(routeName, params));
        },

        // СКРОЛЛ
        updateScrollPosition(event) {
            const wrapper = event.target;

            this.maxScroll = wrapper.scrollWidth - wrapper.clientWidth;
            this.scrollLeft = wrapper.scrollLeft;
        },
        scrollTable(direction) {
            const wrapper = this.$refs.tableWrapper;

            if (!wrapper) return;

            this.maxScroll = wrapper.scrollWidth - wrapper.clientWidth;
            const amount = this.maxScroll / 2;

            wrapper.scrollTo({
                left: direction === 'right'
                    ? Math.min(wrapper.scrollLeft + amount, this.maxScroll)
                    : Math.max(wrapper.scrollLeft - amount, 0),
                behavior: 'smooth',
            });
        },

        // СКРОЛЛ ЗАЖАТИЕМ КНОПКОЙ МЫШИ
        startDrag(event) {
            const wrapper = this.$refs.tableWrapper;

            this.isDragging = true;
            this.startX = event.pageX;
            this.startScrollLeft = wrapper.scrollLeft;

            wrapper.classList.add('dragging');
        },
        drag(event) {
            if (!this.isDragging) return;

            const wrapper = this.$refs.tableWrapper;
            const x = event.pageX;
            const walk = x - this.startX;

            wrapper.scrollLeft = this.startScrollLeft - walk;
        },
        stopDrag() {
            this.isDragging = false;

            const wrapper = this.$refs.tableWrapper;

            if (wrapper) {
                wrapper.classList.remove('dragging');
            }
        },
    },
};
</script>

<template>
    <div class="box">
        <div class="timeline-wrapper">
            <TimeLineHeader :header :division_id="division.id" :dateProp />
            <div
                class="table-wrapper"
                ref="tableWrapper"
                @scroll="updateScrollPosition"
                @mousedown="startDrag"
                @mousemove="drag"
                @mouseup="stopDrag"
                @mouseleave="stopDrag"
            >
                <table class="timeline-grid">
                    <TimeLineThead :allSlots :division_id="division.id" />
                    <TimeLineTbody :allSlots :subscribes :show :division_id="division.id" />
                </table>
            </div>

            <div class="button-move-scroll-container">
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
</template>

<style lang="sass" scoped>
.box
    padding: 24px

.timeline-wrapper
    position: relative
    border: 1px solid #88a2ff
    border-radius: 10px
    background: #fff
    margin: 24px auto
    display: flex
    flex-direction: column
    z-index: 1

    .table-wrapper
        overflow-x: auto

        &.dragging
            cursor: grabbing
            user-select: none

    .button-move-scroll-container
        display: flex
        justify-content: space-between
        padding: 5px 20px
        padding-left: 160px
        margin-bottom: 10px

        position: absolute
        width: 100%
        height: 40px

        right: 0
        bottom: 0

        z-index: 100

        pointer-events: none

        button
            pointer-events: auto

        .button-hidden
            opacity: 0
            pointer-events: none

    .timeline-grid
        width: 100%
        border-collapse: separate
        border-spacing: 0
        table-layout: fixed
        table
            border-radius: 10px

    .timeline-header
        border-top-left-radius: 10px
        border-top-right-radius: 10px
</style>

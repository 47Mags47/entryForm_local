<script>
import { usePage, router } from "@inertiajs/vue3";
import { DateTime, Interval } from "luxon";
import { fixOverflow } from "../../../js/helpers";

export default {
    props: {
        division: Object,
        subscribes: Array,
    },

    computed: {
        dates: () => usePage().props.dates,
        workerIds() {
            let result = [];

            this.subscribes.forEach(function (subscribe) {
                result.push(subscribe.worker_id);
            });

            return [...new Set(result)];
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
        getWorkerFullName(worker_id) {
            const worker = this.subscribes.find(
                subscribe => subscribe.worker.id === worker_id
            )?.worker;

            if (!worker)
                return

            return `${worker.last_name} ${worker.first_name[0].toUpperCase()}.${worker.middle_name[0].toUpperCase()}`
        },
        getClientFullName(worker_id, time) {
            const subscribe = this.subscribes.find(
                (subscribe) =>
                    subscribe.worker_id === worker_id &&
                    subscribe.start_at === time,
            )

            if (!subscribe)
                return

            let initials = ''
            if (subscribe.first_name)
                initials = subscribe.first_name[0].toUpperCase() + '.'

            if (subscribe.middle_name)
                initials += subscribe.middle_name[0].toUpperCase() + '.'

            return `${subscribe.last_name} ${initials}`
        },

        getServiceName(worker_id, time) {
            return this.subscribes.find(
                (subscribe) =>
                    subscribe.worker_id === worker_id &&
                    subscribe.start_at === time,
            )?.service?.name;
        },
        subscribeExist(worker_id, time) {
            return this.subscribes.some(
                (subscribe) =>
                    subscribe.worker_id === worker_id &&
                    subscribe.start_at === time,
            );
        },
        getSubscribe(worker_id, time) {
            return this.subscribes.find(
                (subscribe) =>
                    subscribe.worker_id === worker_id &&
                    subscribe.start_at === time,
            );
        },

        eventBlockClickHandler(worker_id, time) {
            router.get(route('subscribes.show', {
                division: this.division.data.id,
                subscribe: this.getSubscribe(worker_id, time)
            }))
        },
    }
};
</script>

<template>
    <tbody>
        <tr v-if="subscribes.length !== 0" v-for="worker_id in workerIds">
            <td class="user-cell">
                {{ getWorkerFullName(worker_id) }}
            </td>

            <td class="time-slot" v-for="section in interval.splitBy({ minutes: 30 })">
                <div
                    v-if="subscribeExist(worker_id, section.start.toFormat('yyyy-MM-dd HH:mm'))"
                    class="events-track"
                >

                    <div
                        class="event-block"
                        @click="eventBlockClickHandler(worker_id, section.start.toFormat('yyyy-MM-dd HH:mm'))"
                    >
                        <div class="service-name-wrapper">
                            <span> {{ getServiceName(worker_id, section.start.toFormat('yyyy-MM-dd HH:mm')) }} </span>
                        </div>

                        <!-- ПРЕВЬЮ БЛОКА -->
                        <div class="event-preview">
                            <p class="preview-title">
                                {{ getServiceName(worker_id, section.start.toFormat('yyyy-MM-dd HH:mm')) }}
                            </p>

                            <div class="preview-grid">
                                <span> Время: </span>
                                <span> {{ section.start.toFormat("HH:mm") }} </span>

                                <span> Заявитель: </span>
                                <span> {{ getClientFullName(worker_id, section.start.toFormat('yyyy-MM-dd HH:mm')) }} </span>

                                <span> Сотрудник: </span>
                                <span> {{ getWorkerFullName(worker_id) }} </span>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>

        <tr v-else>
            <td class="subscribes-empty" :colspan="interval.splitBy({ minutes: 30 }).length + 1">
                данных нет :(
            </td>
        </tr>

    </tbody>
</template>

<style lang="sass" scoped>
.subscribes-empty
    height: 100px
    padding: 0
    vertical-align: middle !important
    text-align: center
    font-size: 24px

.user-cell
    background: rgb(216, 216, 255)
    font-weight: 500
    font-size: 14px
    display: flex
    justify-content: center
    align-items: center
    color: var(--text-color)
    width: 140px
    min-width: 140px
    white-space: normal

.time-slot
    padding: 6px
    min-width: 400px
    color: var(--text-color)
    position: relative
    text-align: center

    &.empty-state
        background: #f8f8f8
        color: #999
        font-style: italic
        height: 56px
        min-height: 56px

    .events-track
        position: relative
        width: 100%
        height: 100%
        min-height: 56px

        .event-block
            position: absolute
            top: 10%
            width: 100%
            height: 80%
            background: linear-gradient(135deg, #ab9dff, #88a2ff)
            border-radius: 4px
            padding: 4px 6px
            color: #fff
            font-size: 12px
            font-weight: 500
            white-space: nowrap
            overflow: visible
            cursor: pointer
            z-index: 10

            .service-name-wrapper
                height: 100%
                width: 100%
                min-width: 0

                overflow: hidden

                display: flex
                justify-content: center
                align-items: center

                span
                    min-width: 0
                    overflow: hidden
                    text-overflow: ellipsis
                    white-space: nowrap
                    font-size: 12px

            .event-preview
                position: fixed
                bottom: 16px
                left: 50%
                transform: translateX(-50%)

                width: 800px
                padding: 10px

                background: white
                color: var(--text-color)

                border: 1px solid #88a2ff81
                border-radius: 8px
                box-shadow: 0 4px 15px rgba(0, 0, 0, .15)

                opacity: 0
                visibility: hidden
                pointer-events: none

                transition: opacity .15s ease
                z-index: 1000

                display: flex
                flex-direction: column
                gap: 5px
                align-items: start

                white-space: normal

                .preview-title
                    width: 100%
                    font-weight: bold
                    padding: 10px
                    border-bottom: 2px solid #ccc

                .preview-grid
                    align-items: start
                    text-align: left

                    display: grid
                    grid-template-columns: 1fr 1fr
                    align-items: start
                    gap: 5px

                    span
                        font-size: 14px


            &:hover
                z-index: 100

                .event-preview
                    opacity: 1
                    visibility: visible
</style>

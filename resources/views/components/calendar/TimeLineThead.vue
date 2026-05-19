<script>
import dayjs from "dayjs";

export default {
    props: {
        allSlots: Array,
    },

    methods: {
        formatTime(timeString) {
            return dayjs(timeString).format("HH:mm");
        },
    },
};
</script>

<template>
    <thead>
        <tr class="time-row time-header">
            <th class="user-header-cell"></th>

            <template v-if="allSlots.length > 0">
                <th v-for="slot in allSlots" :key="slot" class="time-slot">
                    {{ formatTime(slot) }}
                </th>
            </template>

            <th v-else class="time-slot empty-state">Нет данных</th>
        </tr>
    </thead>
</template>

<style lang="sass" scoped>
thead th
    border: 1px solid #88a2ff81
    padding: 6px
    vertical-align: top
    word-break: break-word
    background: rgb(216, 216, 255)
    color: var(--text-color)
    position: sticky
    top: 0
    z-index: 5

    .time-row.time-header
        text-align: center
        height: 40px

        .time-slot
            padding: 6px 0px
            min-width: 60px

            font-size: 10px
            color: var(--text-color)
            position: relative
            text-align: center

            &.empty-state
                background: #f8f8f8
                color: #999
                font-style: italic
                height: 56px
                min-height: 56px
.user-header-cell
    width: 140px
</style>

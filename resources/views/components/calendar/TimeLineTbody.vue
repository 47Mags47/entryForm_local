<script>
import EventBlock from "./EventBlock.vue";

export default {
    components: {
        EventBlock,
    },

    props: {
        division_id: Number,
        allSlots: Array,
        subscribes: Array,
        show: Function,
    },
};
</script>

<template>
    <tbody>
        <tr v-for="subscribe in subscribes" :key="subscribe.worker.data.id">
            <td class="user-cell">
                {{
                    (() => {
                        const worker = subscribe.worker.data;
                        const lastName = worker.last_name;
                        const firstName = worker.first_name;
                        const middleName = worker.middle_name;

                        if (lastName) {
                            const parts = [lastName];
                            if (firstName) parts.push(firstName[0] + ".");
                            if (middleName) parts.push(middleName[0] + ".");
                            return parts.join(" ");
                        } else {
                            const parts = [];
                            if (firstName) parts.push(firstName);
                            if (middleName) parts.push(middleName);
                            return parts.join(" ");
                        }
                    })()
                }}
            </td>

            <template v-if="allSlots.length > 0">
                <td
                    v-for="slot in allSlots"
                    :key="slot + subscribe.worker.data.id"
                    class="time-slot"
                >
                    <div class="events-track">
                        <div
                            v-for="(record, i) in subscribe.timeline[slot]"
                            :key="i"
                        >
                            <EventBlock
                                :record="record"
                                :show="show"
                                :division_id="division_id"
                            />
                        </div>
                    </div>
                </td>
            </template>

            <td v-else class="time-slot empty-state" colspan="1">
                <div class="no-data-placeholder"></div>
            </td>
        </tr>
    </tbody>
</template>

<style lang="sass" scoped>
th, td
    border: 1px solid #88a2ff81
    padding: 6px
    vertical-align: top
    word-break: break-word

.user-cell
    background: rgb(216, 216, 255)
    font-weight: 500
    font-size: 13px
    color: var(--text-color)
    width: 140px
    min-width: 140px
    white-space: normal

.time-slot
    padding: 6px 0px
    min-width: 60px
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
        height: 56px
        min-height: 56px
</style>

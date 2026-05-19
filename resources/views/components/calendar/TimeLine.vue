<script>
import { router, usePage } from "@inertiajs/vue3";

import TimeLineHeader from "./TimeLineHeader.vue";
import TimeLineThead from "./TimeLineThead.vue";
import TimeLineTbody from "./TimeLineTbody.vue";

export default {
    components: {
        TimeLineHeader,
        TimeLineThead,
        TimeLineTbody,
    },

    props: {
        header: String,
    },

    data() {
        const division = usePage().props.division.data;
        const subscribes = usePage().props.subscribes;
        const dateProp = usePage().props.dates;

        return {
            dateProp,
            division,
            subscribes,
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
    },

    methods: {
        show(routeName, params) {
            router.get(route(routeName, params));
        },
    },
};
</script>

<template>
    <div class="box">
        <div class="timeline-wrapper">
            <TimeLineHeader :header :division_id="division.id" :dateProp />
            <table class="timeline-grid">
                <TimeLineThead :allSlots :division_id="division.id" />
                <TimeLineTbody :allSlots :subscribes :show :division_id="division.id" />
            </table>
        </div>
    </div>
</template>

<style lang="sass" scoped>
.box
    padding: 24px

.timeline-wrapper
    border: 1px solid #88a2ff
    border-radius: 10px
    background: #fff
    margin: 24px auto
    overflow: auto
    display: flex
    flex-direction: column

    .timeline-grid
        width: 100%
        border-collapse: separate
        border-spacing: 0
        table-layout: fixed
</style>

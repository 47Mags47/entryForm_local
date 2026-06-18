<script>
import { nextTick } from "vue";

export default {
    props: {
        division_id: Number,
        show: Function,
        record: Object,
    },

    methods: {
        setTruncateTitle(el, text) {
            if (!el) return;

            nextTick(() => {
                if (el.scrollWidth > el.clientWidth) {
                    el.title = text;
                } else {
                    el.title = "";
                }
            });
        },
        durationToMinutes(d) {
            const [h, m, s] = d.split(":").map(Number);
            return h * 60 + m + (s || 0) / 60;
        },
    },

    mounted() {
        setTimeout(() => {
            const blocks = document.getElementsByClassName("event-content");
            for (let i = 0; i < blocks.length; i++) {
                const block = blocks[i];
                if (block.scrollWidth > block.clientWidth) {
                    block.innerText = "...";
                }
            }
        }, 0);
    },
};
</script>

<template>
    <div
        :ref="(el) => setTruncateTitle(el, `${record.data.service.name} ${record.data.date_start}`)"
        class="event-block"
        @click="show('subscribes.show', { division: this.division_id, subscribe: record.data.id })"
        :style="{
            left: record.data.shift + '%',
            width:(durationToMinutes(record.data.service.duration) / 30) * 100 + '%',
        }"
    >
        <div class="event-content">
            {{ record.data.service.name }}
            {{ record.data.date_start }}
        </div>
    </div>
</template>

<style lang="sass" scoped>
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
</style>

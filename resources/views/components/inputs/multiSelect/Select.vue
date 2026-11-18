<script>
import { defineAsyncComponent } from 'vue';

export default {
    components: {
        MultiSelect: defineAsyncComponent(() => import('./types/MultiSelect.vue')),
        SingleSelect: defineAsyncComponent(() => import('./types/SingleSelect.vue')),
    },

    props: {
        type: {
            type: String,
            default: 'single'
        }
    }
}
</script>

<template>
    <SingleSelect v-if="type === 'single'" v-bind="$attrs" />
    <MultiSelect  v-if="type === 'multi'"  v-bind="$attrs" />
</template>

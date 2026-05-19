<script>
import { default as FlashMessage } from "../includes/FlashMessage.vue";

export default {
    components: {
        FlashMessage,
    },

    props: {
        name: String,
    },
};
</script>

<template>
    <div :class="['layout', name]">
        <FlashMessage />
        <slot />
    </div>
</template>

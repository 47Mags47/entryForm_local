<script>
import { default as BaseLayout } from "./BaseLayout.vue";

export default {
    components: {
        BaseLayout,
    },
};
</script>

<template>
    <BaseLayout name="guest">
        <slot />
    </BaseLayout>
</template>

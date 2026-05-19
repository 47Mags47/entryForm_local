<script>
import { Link } from "@inertiajs/vue3";
import { default as PenIco } from "../../icons/PenIco.vue";

export default {
    components: {
        Link,
        PenIco,
    },

    props: {
        href: {
            type: String,
            required: true,
        },
    },
};
</script>

<template>
    <Link :href="href" class="button blue-button" v-bind="$attrs">
        <PenIco />
    </Link>
</template>

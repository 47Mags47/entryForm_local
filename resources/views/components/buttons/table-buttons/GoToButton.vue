<script>
import { Link } from "@inertiajs/vue3";
import { default as GoToIco } from "../../icons/GoToIco.vue";

export default {
    components: {
        Link,
        GoToIco,
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
        <GoToIco />
    </Link>
</template>

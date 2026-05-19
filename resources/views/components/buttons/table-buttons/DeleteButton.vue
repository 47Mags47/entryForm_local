<script>
import { router } from "@inertiajs/vue3";

import { default as RedButton } from "../../buttons/RedButton.vue";
import { default as TrashIco } from "../../icons/TrashIco.vue";

export default {
    components: {
        RedButton,
        TrashIco,
    },

    props: {
        href: {
            type: String,
            required: true,
        },
    },

    methods: {
        destroy() {
            if (confirm("Вы уверены, что хотите удалить запись?")) {
                router.delete(this.href);
            }
        },
    },
};
</script>

<template>
    <RedButton @click="destroy">
        <TrashIco />
    </RedButton>
</template>

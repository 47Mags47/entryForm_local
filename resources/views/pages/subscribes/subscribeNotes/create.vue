<script>
import {
    VerticalForm,
    TextArea
 } from '@components';
import { router, usePage } from '@inertiajs/vue3';
export default {
    components: {
        VerticalForm,
        TextArea
    },

    data() {
        return {
            note: null
        }
    },

    methods: {
        onSubmit(e) {
            e.preventDefault();
            router.post(route("subscribes.note.store", {
                    division: this.division.id,
                    subscribe: this.subscribe.id
                }),
                {
                    note: this.note
                }
            )
        },
    },

    computed: {
        division: () => usePage().props.division.data,
        subscribe: () => usePage().props.subscribe.data
    }
}
</script>

<template>
    <VerticalForm header="Новая запись" sbm="добавить" :handleSubmit="onSubmit">
        <TextArea
            name="note"
            :value="note"
            label="Заметка"
            @update:value="(val) => note = val"
        />
    </VerticalForm>
</template>

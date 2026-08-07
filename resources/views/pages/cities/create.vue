<script>
import { useForm } from "@inertiajs/vue3";

import { VerticalForm, StringInput } from "@components";

export default {
    components: {
        VerticalForm,
        StringInput,
    },

    data() {
        return {
            form: useForm({
                name: "",
            }),
        };
    },

    methods: {
        onSubmit(e) {
            e.preventDefault();

            this.form.post(route("cities.store"));
        },
    },
};
</script>

<template>
    <VerticalForm header="Город" sbm="Отправить" :handleSubmit="onSubmit">
        <StringInput
            label="Наименование"
            name="name"
            :value="form.name"
            @update:value="(val) => (form.name = val)"
            autocomplete="current-name"
        />
    </VerticalForm>
</template>

<script>
import { useForm } from "@inertiajs/vue3";

import { AuthenticatedLayout } from "@layouts";

import { VerticalForm, StringInput } from "@components";

export default {
    components: {
        AuthenticatedLayout,
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
    <AuthenticatedLayout>
        <VerticalForm header="Город" sbm="Отправить" :handleSubmit="onSubmit">
            <StringInput
                label="Наименование"
                name="name"
                :value="form.name"
                @update:value="(val) => (form.name = val)"
                autocomplete="current-name"
            />
        </VerticalForm>
    </AuthenticatedLayout>
</template>

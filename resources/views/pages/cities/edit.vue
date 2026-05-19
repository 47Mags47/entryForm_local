<script>
import { usePage, useForm } from "@inertiajs/vue3";

import { AuthenticatedLayout } from "@layouts";

import { VerticalForm, StringInput } from "@components";

export default {
    components: {
        AuthenticatedLayout,
        VerticalForm,
        StringInput,
    },

    data() {
        const city = usePage().props.city.data;

        return {
            city,
            form: useForm({
                name: city.name,
            }),
        };
    },

    methods: {
        onSubmit(e) {
            e.preventDefault();
            this.form.put(route("cities.update", { city: this.city.id }));
        },
    },
};
</script>

<template>
    <AuthenticatedLayout>
        <VerticalForm header="Город" sbm="Сохранить" :handleSubmit="onSubmit">
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

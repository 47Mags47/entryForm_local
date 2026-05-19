<script>
import { usePage, useForm } from "@inertiajs/vue3";
import { AuthenticatedLayout } from "@layouts";
import { VerticalForm, TimePicker, TextArea } from "@components";

export default {
    components: {
        AuthenticatedLayout,
        VerticalForm,
        TimePicker,
        TextArea,
    },

    data() {
        const services = usePage().props.services.data;
        return {
            form: useForm({
                name: services.name,
                duration: services.duration,
            }),
            services,
        };
    },

    methods: {
        onSubmit(e) {
            e.preventDefault();
            this.form.put(
                route("services.update", { service: this.services.id }),
            );
        },
    },
};
</script>

<template>
    <AuthenticatedLayout>
        <VerticalForm header="Услуги" sbm="Сохранить" :handleSubmit="onSubmit">
            <TextArea
                label="Наименование"
                name="name"
                :value="form.name"
                @update:value="(val) => (form.name = val)"
                :rows="4"
            />
            <TimePicker
                v-model="form.duration"
                mode="time"
                name="duration"
                label="Продолжительность"
            />
        </VerticalForm>
    </AuthenticatedLayout>
</template>

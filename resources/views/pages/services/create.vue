<script>
import { useForm } from "@inertiajs/vue3";
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
        return {
            form: useForm({
                name: "",
                duration: "",
            }),
        };
    },

    methods: {
        onSubmit(e) {
            e.preventDefault();
            this.form.post(route("services.store"));
        },
    },
};
</script>

<template>
    <AuthenticatedLayout>
        <VerticalForm header="Услуги" sbm="Отправить" :handleSubmit="onSubmit">
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

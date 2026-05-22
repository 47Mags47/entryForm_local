<script>
import { useForm, usePage } from "@inertiajs/vue3";
import { AuthenticatedLayout } from "@layouts";
import { VerticalForm, StringInput, Select, DatePicker } from "@components";

export default {
    components: {
        AuthenticatedLayout,
        VerticalForm,
        StringInput,
        Select,
        DatePicker
    },

    computed: {
        division: () => usePage().props.division.data,
        services: () => usePage().props.services.data.map((service) => ({
            label: service.name,
            value: service.id
        })),
        workers: () => usePage().props.workers.data.map((worker) => ({
            label: `${worker.last_name} ${worker.first_name[0]} ${worker.middle_name[0]}`,
            value: worker.id
        })),
    },

    data() {
        return {
            form: useForm({
                first_name: "",
                last_name: "",
                middle_name: "",
                phone: "",
                email: "",
                service_id: "",
                worker_id: "",
                start_at: '',
            }),
        };
    },

    methods: {
        onSubmit(e) {
            e.preventDefault();

            this.form.post(route("subscribes.store", { division: this.division.id }));
        },
    },
}
</script>

<template>
<AuthenticatedLayout>
    <VerticalForm header="Новая запись" sbm="Отправить" :handleSubmit="onSubmit">
        <StringInput
            label="Фамилия"
            name="last_name"
            :value="form.last_name"
            @update:value="(val) => (form.last_name = val)"
        />
        <StringInput
            label="Имя"
            name="first_name"
            :value="form.first_name"
            @update:value="(val) => (form.first_name = val)"
        />
        <StringInput
            label="Отчество"
            name="middle_name"
            :value="form.middle_name"
            @update:value="(val) => (form.middle_name = val)"
        />
        <StringInput
            label="Телефон"
            name="phone"
            placeholder="+7 (___) ___-__-__"
            :value="form.phone"
            @update:value="(val) => (form.phone = val)"
        />
        <StringInput
            label="E-mail"
            name="email"
            placeholder="example@mail.ru"
            :value="form.email"
            @update:value="(val) => (form.email = val)"
        />
        <Select
            label="Услуга"
            name="service_id"
            v-model="form.service_id"
            :options="services"
            placeholder="Услуга"
        />
        <Select
            label="Сотрудник"
            name="worker_id"
            v-model="form.worker_id"
            :options="workers"
            placeholder=""
        />
        <DatePicker
            label="Дата"
            name="start_at"
            v-model="form.start_at"
        />

    </VerticalForm>
</AuthenticatedLayout>
</template>

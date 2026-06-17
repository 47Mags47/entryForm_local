<script>
import { useForm, usePage } from "@inertiajs/vue3";
import { AuthenticatedLayout } from "@layouts";
import { VerticalForm, StringInput, Select, NumberInput, EmailInput } from "@components";
import axios from "axios";
import DatePicker from "../../components/inputs/datePicker_new/DatePicker.vue";
import { DateTime } from "luxon";

export default {
    components: {
        AuthenticatedLayout,
        VerticalForm,
        StringInput, Select, NumberInput, EmailInput,
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

        watchedFormFields() {
            return {
                worker_id: this.form.worker_id,
                service_id: this.form.service_id,
                start_date: this.form.start_date,
            }
        },
        timePlaceHolder() {
            if (this.availableTime.length === 0)
                return 'Нет доступного времени'
            return 'Выберите время'
        }
    },

    watch: {
        watchedFormFields(newValue) {
            if (
                newValue.worker_id &&
                newValue.service_id &&
                newValue.start_date
            ) {
                axios.get(route('api.avalibleTime.index'), {
                    params: {
                        worker_id: newValue.worker_id,
                        service_id: newValue.service_id,
                        date: newValue.start_date,
                    }
                }).then(res => {
                    this.availableTime = [...res.data]
                }).catch(err => {
                    this.availableTime = []
                    console.error('Ошибка в axios-api-запросе api.avalibleTime.index:', err)
                })
            }
        }
    },

    data() {
        return {
            availableTime: [],
            form: useForm({
                first_name: "",
                last_name: "",
                middle_name: "",
                phone: "",
                email: "",
                service_id: "",
                worker_id: "",
                start_date: '',
                start_time: ''
            }),
            now: DateTime.now(),
            startTime: DateTime.now().plus({'day': 1}),
            endTime: DateTime.now().plus({'month': 1})
        };
    },

    methods: {
        onSubmit(e) {
            e.preventDefault();

            this.form
                .transform((data) => ({
                    ...data,
                    start_date: data.start_date
                }))
                .post(
                    route("subscribes.store", {
                        division: this.division.id
                    })
                );
        },
    },
}
</script>

<template>
<AuthenticatedLayout>
    <VerticalForm header="Новая запись" sbm="Отправить" :handleSubmit="onSubmit">
        <div @click="console.log(availableTime)">click</div>
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
        <NumberInput
            label="Телефон"
            name="phone"
            placeholder="+7 (___) ___-__-__"
            :value="form.phone"
            @update:value="(val) => (form.phone = val)"
        />
        <EmailInput
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
            name="start_date"
            :start="startTime"
            :end="endTime"
            :showAvailable="false"
            @update:value="(val) => form.start_date = val"
        />
        <Select
            label="Время"
            name="start_time"
            v-model="form.start_time"
            :options="availableTime"
            :placeholder="timePlaceHolder ?? ''"
        />
    </VerticalForm>
</AuthenticatedLayout>
</template>

<style lang="sass" scoped>
#timepicker
    :deep(.datepicker-input)
        display: flex
        flex-direction: column
        input
            width: 100%
        label
            padding: 10px 0 3px 0
            color: var(--label-color)
</style>

<script>
import { usePage, router } from "@inertiajs/vue3";
import { VerticalForm, DatePicker, Select } from "@components";
import axios from "axios";

export default {
    components: {
        VerticalForm,
        DatePicker,
        Select,
    },

    data() {
        return {
            replacement: "",
            workers: [],
            date_start: null,
            date_end: null,
        };
    },

    watch: {
        date_start(newDateStart) {
            if (!newDateStart || !this.date_end)
                return

            axios.get(route('api.availableWorkersFromDates.index'), {
                params: {
                    worker_id: this.worker.id,
                    division_id: this.division.id,
                    date_start: newDateStart,
                    date_end: this.date_end,
                }
            })
            .then(res => {
                this.workers = Object.entries(res.data).map(
                    ([_, workerData]) => ({
                        value: workerData.id,
                        label: `${workerData.last_name} ${workerData.first_name?.charAt(0).toUpperCase()}.${workerData.middle_name?.charAt(0).toUpperCase()}`,
                    }),
                );
            })
            .catch(err => {
                this.availableTime = []
                console.error(
                    'Ошибка в axios API-запросе:',
                    err
                )
            })
        },
        date_end(newDateEnd) {
            if (!newDateEnd || !this.date_start)
                return

            axios.get(route('api.availableWorkersFromDates.index'), {
                params: {
                    worker_id: this.worker.id,
                    division_id: this.division.id,
                    date_start: this.date_start,
                    date_end: newDateEnd,
                }
            })
            .then(res => {
                this.workers = Object.entries(res.data).map(
                    ([_, workerData]) => ({
                        value: workerData.id,
                        label: `${workerData.last_name} ${workerData.first_name?.charAt(0).toUpperCase()}.${workerData.middle_name?.charAt(0).toUpperCase()}`,
                    }),
                );
            })
            .catch(err => {
                this.availableTime = []
                console.error(
                    'Ошибка в axios API-запросе:',
                    err
                )
            })
        }
    },

    computed: {
        current_user: () => usePage().props.current_user.data,
        division: () => usePage().props.current_division.data,
        worker: () => usePage().props.worker.data,
    },

    methods: {
        onSubmit(e) {
            e.preventDefault();

            router.post(
                route("weekends.store", {
                    division: this.division.id,
                    worker: this.worker.id,
                }),
                {
                    replacement_id: this.replacement,
                    date_start: this.date_start,
                    date_end: this.date_end,
                },
            );
        },
    },
};
</script>

<template>
    <VerticalForm
        header="Новая запись (отпуск)"
        sbm="сохранить"
        :handleSubmit="onSubmit"
    >
        <!-- HACK переделать в range-date. Не добавляю, потому что не показывается сообщение ошибки в инпутах, если есть -->
        <DatePicker
            label="Начало"
            name="date_start"
            :showAvailable="false"
            @update:value="(val) => (date_start = val)"
        />
        <DatePicker
            label="Конец"
            name="date_end"
            :showAvailable="false"
            @update:value="(val) => (date_end = val)"
        />
        <Select
            label="Замещающий"
            name="replacement_id"
            v-model="replacement"
            :options="workers"
            placeholder="Выберите сотрудника"
        />
    </VerticalForm>
</template>

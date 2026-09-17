<script>
import { usePage, router } from "@inertiajs/vue3";
import { VerticalForm, DatePicker, Select } from "@components";
import axios from "axios";

export default {
    components: {
        VerticalForm,
        DatePicker, Select
    },

    data() {
        return {
            replacement: usePage().props.weekend.data.replacement?.id ?? '',
            date_start: usePage().props.weekend.data.date_start ?? null,
            date_end: usePage().props.weekend.data.date_end ?? null,
            workers: Object.entries(usePage().props.workers.data).map(
                    ([_, workerData]) => ({
                        value: workerData.id,
                        label: workerData.full_name,
                    }),
                )
        }
    },

    watch: {
        date_start(newDateStart) {
            if (!newDateStart || !this.date_end)
                return

            axios.get(route('api.availableWorkersFromDates.index'), {
                params: {
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
        weekend: () => usePage().props.weekend.data
    },

    methods: {
        onSubmit(e) {
            e.preventDefault();

            router.put(
                route("weekends.update", {
                    division:   this.division.id,
                    worker:     this.worker.id,
                    weekend:    this.weekend.id,
                }),
                {
                    replacement_id: this.replacement,
                    date_start:     this.date_start,
                    date_end:       this.date_end,
                },
            );
        },
    },
};
</script>

<template>
    <VerticalForm
        header="Редактирование записи (отпуск)"
        sbm="сохранить"
        :handleSubmit="onSubmit"
    >
        <DatePicker
            label="Начало"
            name="date_start"
            :showAvailable="false"
            :value="date_start"
            @update:value="(val) => (date_start = val)"
        />
        <DatePicker
            label="Конец"
            name="date_end"
            :showAvailable="false"
            :value="date_end"
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

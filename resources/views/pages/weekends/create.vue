<script>
import { usePage, router } from "@inertiajs/vue3";
import { VerticalForm, DatePicker, Select } from "@components";

export default {
    components: {
        VerticalForm,
        DatePicker,
        Select,
    },

    data() {
        return {
            replacement: "",
            date_start: null,
            date_end: null,
        };
    },

    computed: {
        current_user: () => usePage().props.current_user.data,
        division: () => usePage().props.current_division.data,
        worker: () => usePage().props.worker.data,

        workers() {
            return Object.entries(usePage().props.workers.data).map(
                ([id, workerData]) => ({
                    value: workerData.id,
                    label: workerData.full_name,
                }),
            );
        },
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
        <Select
            label="Замещающий"
            name="replacement_id"
            v-model="replacement"
            :options="workers"
            placeholder="Выберите сотрудника"
        />
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
    </VerticalForm>
</template>

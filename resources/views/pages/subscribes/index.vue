<script>
import { usePage, useForm, router } from "@inertiajs/vue3";
import { AuthenticatedLayout } from "@layouts";
import { DivisionTab } from "@includes";
import { Table, GoToButton, AddButton, DeleteButton, BlueButton, DatePicker, DownloadIco } from "@components";
import { DateTime } from "luxon";

export default {
    components: {
        AuthenticatedLayout,
        DivisionTab,
        Table,
        GoToButton,
        DeleteButton,
        AddButton,
        BlueButton,
        DatePicker,
        DownloadIco
    },

    data() {
        return {
            isLoading: false,

            selectedDate        : null,
            startDate: { from: DateTime.now().startOf('month'), to: null },

            form: useForm({
                from: DateTime.now().startOf('month'),
                to: null,
            }),
        }
    },

    computed: {
        current_user: () => usePage().props.current_user.data,
        division: () => usePage().props.division.data,
        subscribes: () => usePage().props.subscribes,

        columns() {
            return this.current_user.role.code === "admin"
                ? [
                    { key: "last_name", label: "Фамилия", width: "150px" },
                    { key: "first_name", label: "Имя", width: "150px" },
                    { key: "middle_name", label: "Отчество", width: "150px" },
                    {
                        key: ["division", "name"],
                        label: "Подразделение",
                        width: "400px",
                    },
                    { key: ["service", "name"], label: "Услуга" },
                    {
                        key: "start_at",
                        label: "Дата записи",
                        splitDateTime: false,
                        width: "150px",
                    },
                    {
                        key: ["worker", "name"],
                        label: "Специалист",
                        width: "170px",
                    },
                    { key: "actions", label: "", width: "60px" },
                ]
                : [
                    { key: "last_name", label: "Фамилия", width: "150px" },
                    { key: "first_name", label: "Имя", width: "150px" },
                    { key: "middle_name", label: "Отчество", width: "150px" },
                    { key: ["service", "name"], label: "Услуга" },
                    {
                        key: "start_at",
                        label: "Дата записи",
                        splitDateTime: false,
                        width: "170px",
                    },
                    {
                        key: ["worker", "name"],
                        label: "Специалист",
                        width: "150px",
                    },
                    { key: "actions", label: "", width: "60px" },
                ];
        }
    },

    methods: {
        hasDelete(subscribe) {
            if (this.current_user.role.code === "admin")
                return true

            if (this.current_user.role.code === "division_admin")
                return true

            if (this.current_user.role.code === "division_worker")
                return subscribe.worker.id === this.current_user.id
        },
        updateDateBetween(newDateBetween) {
            this.form.from   =   newDateBetween.from
            this.form.to     =   newDateBetween.to
        },

        applyRange() {
            this.form
            .transform(data => ({
                ...data,
                from: data.from?.toFormat('yyyy-MM-dd'),
                to: data.to?.toFormat('yyyy-MM-dd'),
            }))
            .get(route('subscribes.index', {
                division: this.division.id,
            }), {
                preserveState: true,
                preserveScroll: true,
            });
        },

        subscribesExport() {
            window.open(route('subscribes.export', { division: this.division.id }))
        }
    },

    mounted() {
        router.on('start', () => {
            this.isLoading = true;
        });

        router.on('finish', () => {
            this.isLoading = false;
        });
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <DivisionTab current="subscribes">
            <Table :data="subscribes" :columns="columns" header="Список обращений" :isLoading="isLoading">
                <template #toolbar-left>
                    <DatePicker
                        :isRange="true"
                        name="date"
                        :value="startDate"
                        :showAvailable="false"
                        @update:value="updateDateBetween"
                    />
                    <BlueButton :handle-click="applyRange"> применить </BlueButton>
                </template>
                <template #toolbar-right>

                    <AddButton :href="route('subscribes.create', { division: division.id })" />
                </template>
                <template #actions="{ row }">
                    <DeleteButton v-if="hasDelete(row)" :href="route('subscribes.destroy', {
                        division: division.id,
                        subscribe: row.id,
                    })" />
                    <GoToButton :href="route('subscribes.show', {
                        division: division.id,
                        subscribe: row.id,
                    })
                        " />
                </template>
            </Table>
        </DivisionTab>
    </AuthenticatedLayout>
</template>

<style lang="sass">
.toolbar-left
    gap: 10px

    .date-picker-button
        background: blue
</style>

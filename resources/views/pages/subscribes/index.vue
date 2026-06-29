<script>
import { usePage, useForm } from "@inertiajs/vue3";
import { AuthenticatedLayout } from "@layouts";
import { DivisionTab } from "@includes";
import { Table, GoToButton, AddButton, DeleteButton, BlueButton, DatePicker } from "@components";
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
        DatePicker
    },

    data() {
        return {
            selectedDate        : null,
            startDate: { from: DateTime.now(), to: null },

            form: useForm({
                from: null,
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
                        width: "200px",
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
            if (newDateBetween?.from?.isValid && newDateBetween?.to?.isValid) {
                this.form.from   =   newDateBetween.from.toFormat('yyyy-MM-dd')
                this.form.to     =   newDateBetween.to.toFormat('yyyy-MM-dd')
            }
        },

        applyRange() {
            this.form.get(route('subscribes.index', {
                division: this.division.id,
            }), {
                preserveState: true,
                preserveScroll: true,
            });
        },
    },
};
</script>

<template>
    <AuthenticatedLayout>
        <DivisionTab current="subscribes">
            <Table :data="subscribes" :columns="columns" header="Список обращений">
                <template #toolbar-left>
                    <DatePicker
                        :isRange="true"
                        name="date"
                        :value="startDate"
                        :showAvailable="false"
                        @update:value="updateDateBetween"
                    />
                    <BlueButton :handle-click="applyRange" :disabled="form.from > form.to"> применить </BlueButton>
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

<script>
import { usePage } from "@inertiajs/vue3";
import { AuthenticatedLayout } from "@layouts";
import { DivisionTab } from "@includes";
import { Table, GoToButton, AddButton, DeleteButton } from "@components";

export default {
    components: {
        AuthenticatedLayout,
        DivisionTab,
        Table,
        GoToButton,
        DeleteButton,
        AddButton
    },

    computed:{
        role: () => usePage().props.current_user.data.role.code,
        division: () => usePage().props.division.data,
        subscribes: () => usePage().props.subscribes,
    },

    data() {
        const page = usePage();

        let columns = [];

        if (this.role === "admin") {
            columns = [
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
            ];
        } else {
            columns = [
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

        return {
            columns,
        };
    },
};
</script>

<template>
    <AuthenticatedLayout>
        <DivisionTab current="subscribes">
            <Table
                :data="subscribes"
                :columns="columns"
                header="Список обращений"
            >
                <template #toolbar-right>
                    <AddButton :href="route('subscribes.create', {division: division.id})" />
                </template>
                <template #actions="{ row }">
                    <DeleteButton
                        :href="route('subscribes.destroy', {
                            division: division.id,
                            subscribe: row.id,
                        })"
                    />
                    <GoToButton
                        :href="
                            route('subscribes.show', {
                                division: division.id,
                                subscribe: row.id,
                            })
                        "
                    />
                </template>
            </Table>
        </DivisionTab>
    </AuthenticatedLayout>
</template>

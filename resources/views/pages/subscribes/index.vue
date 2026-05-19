<script>
import { usePage } from "@inertiajs/vue3";
import { AuthenticatedLayout } from "@layouts";
import { DivisionTab } from "@includes";
import { Table, GoToButton } from "@components";

export default {
    components: {
        AuthenticatedLayout,
        DivisionTab,
        Table,
        GoToButton,
    },

    data() {
        const page = usePage();
        const role = page.props.current_user.data.role.code;
        const division = page.props.division.data;
        const subscribes = page.props.subscribes;

        let columns = [];

        if (role === "admin") {
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
                    splitDateTime: true,
                    width: "105px",
                },
                {
                    key: ["worker", "name"],
                    label: "Специалист",
                    width: "150px",
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
                    splitDateTime: true,
                    width: "105px",
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
            role,
            division,
            subscribes,
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
                <template #actions="{ row }">
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

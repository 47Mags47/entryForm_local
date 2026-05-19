<script>
import { usePage } from "@inertiajs/vue3";
import { AuthenticatedLayout } from "@layouts";
import { DivisionTab } from "@includes";
import { Table, EditButton } from "@components";

export default {
    components: {
        AuthenticatedLayout,
        DivisionTab,
        Table,
        EditButton,
    },
    data() {
        return {
            columns: [
                {
                    label: "ФИО",
                    render: (row) => {
                        const lastName = row.last_name || "";
                        const firstNameInitial = row.first_name
                            ? row.first_name[0] + "."
                            : "";
                        const middleNameInitial = row.middle_name
                            ? row.middle_name[0] + "."
                            : "";

                        const result = [
                            lastName,
                            firstNameInitial,
                            middleNameInitial,
                        ]
                            .filter((part) => part !== "")
                            .join(" ");

                        return result || "-";
                    },
                },
                { key: "email", label: "Email" },
                { key: "action", label: "" },
            ],
        };
    },
    computed: {
        division() {
            const division = usePage().props.division.data;
            return division;
        },
        admins() {
            const admins = usePage().props.users;
            return admins;
        },
    },
};
</script>

<template>
    <AuthenticatedLayout>
        <DivisionTab current="admins">
            <Table :data="admins" :columns="columns">
                <template #toolbar-right>
                    <EditButton
                        :href="
                            route('division-admins.create', {
                                division: division.id,
                            })
                        "
                    />
                </template>
            </Table>
        </DivisionTab>
    </AuthenticatedLayout>
</template>

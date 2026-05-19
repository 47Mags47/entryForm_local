<script>
import { usePage } from "@inertiajs/vue3";

import { AuthenticatedLayout } from "@layouts";

import { Table, EditButton, DeleteButton, AddButton } from "@components";

export default {
    components: {
        AuthenticatedLayout,
        Table,
        EditButton,
        DeleteButton,
        AddButton,
    },

    data() {
        return {
            columns: [
                { key: "name", label: "Наименовение" },
                { key: "actions", label: "" },
            ],
        };
    },

    computed: {
        division_group() {
            const division_group = usePage().props.division_group;
            return division_group;
        },
    },
};
</script>

<template>
    <AuthenticatedLayout>
        <Table :data="division_group" :columns="columns" header="Группы подразделений">
            <template #toolbar-right>
                <AddButton :href="route('division-group.create')" />
            </template>

            <template #actions="{ row }">
                <EditButton :href="route('division-group.edit', row)" />
                <DeleteButton :href="route('division-group.destroy', row)" />
            </template>
        </Table>
    </AuthenticatedLayout>
</template>

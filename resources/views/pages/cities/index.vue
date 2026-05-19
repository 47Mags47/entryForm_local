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
        cities() {
            const cities = usePage().props.cities;
            return cities;
        },
    },
};
</script>

<template>
    <AuthenticatedLayout>
        <Table :data="cities" :columns="columns" header="Города">
            <template #toolbar-right>
                <AddButton :href="route('cities.create')" />
            </template>

            <template #actions="{ row }">
                <EditButton :href="route('cities.edit', row)" />
                <DeleteButton :href="route('cities.destroy', row)" />
            </template>
        </Table>
    </AuthenticatedLayout>
</template>

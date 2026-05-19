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
                { key: "name", label: "Наименование" },
                { key: "duration", label: "Время", width: "100px" },
                { key: "actions", label: "", width: "118px" },
            ],
        };
    },

    computed: {
        services() {
           return usePage().props.services;
        },
    },
};
</script>

<template>
    <AuthenticatedLayout>
        <Table :data="services" :columns="columns" header="Услуга">
            <template #toolbar-right>
                <AddButton href="/services/create" />
            </template>

            <template #actions="{ row }">
                <EditButton :href="route('services.edit', row)" />
                <DeleteButton :href="route('services.destroy', row)" />
            </template>
        </Table>
    </AuthenticatedLayout>
</template>

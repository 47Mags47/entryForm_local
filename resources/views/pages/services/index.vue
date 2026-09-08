<script>
import { usePage } from "@inertiajs/vue3";
import { Table, EditButton, DeleteButton, AddButton } from "@components";

export default {
    components: {
        Table,
        EditButton,
        DeleteButton,
        AddButton,
    },

    data() {
        return {
            search: '',
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

        // HACK перенести на бэк
        filteredServices() {
            const search = this.search.toLowerCase().trim();

            if (!search) {
                return this.services;
            }

            return {
                ...this.divisions,
                data: this.services.data.filter(service =>
                    service.name.toLowerCase().includes(search)
                ),
            };
        },
    },
};
</script>

<template>
    <Table :data="filteredServices" :columns="columns" header="Услуга">
        <template #toolbar-left>
            <input v-model="search" type="text" placeholder="поиск.." class="search-input"/>
        </template>

        <template #toolbar-right>
            <AddButton href="/services/create" />
        </template>

        <template #actions="{ row }">
            <EditButton :href="route('services.edit', row)" />
            <DeleteButton :href="route('services.destroy', row)" />
        </template>
    </Table>
</template>

<style lang="sass" scoped>
.search-input
    width: 260px
</style>

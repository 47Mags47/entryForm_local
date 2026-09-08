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

        // HACK перенести на бэк
        filteredCities() {
            const search = this.search.toLowerCase().trim();

            if (!search) {
                return this.cities;
            }

            return {
                ...this.divisions,
                data: this.cities.data.filter(city =>
                    city.name.toLowerCase().includes(search)
                ),
            };
        },
    },
};
</script>

<template>
    <Table :data="filteredCities" :columns="columns" header="Города">
        <template #toolbar-left>
            <input v-model="search" type="text" placeholder="поиск.." class="search-input"/>
        </template>

        <template #toolbar-right>
            <AddButton :href="route('cities.create')" />
        </template>

        <template #actions="{ row }">
            <EditButton :href="route('cities.edit', row)" />
            <DeleteButton :href="route('cities.destroy', row)" />
        </template>
    </Table>
</template>

<style lang="sass" scoped>
.search-input
    width: 260px
</style>

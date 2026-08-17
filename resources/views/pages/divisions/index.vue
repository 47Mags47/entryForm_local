<script>
import { usePage } from "@inertiajs/vue3";
import {
    Table,
    EditButton,
    DeleteButton,
    AddButton,
    GoToButton,
} from "@components";

export default {
    components: {
        Table,
        EditButton,
        DeleteButton,
        AddButton,
        GoToButton,
    },

    data() {
        return {
            search: '',
            columns: [
                { key: "name", label: "Наименование", width: "342px" },
                { key: "address", label: "Адрес" },
                { key: ["city", "name"], label: "Город", width: "160px" },
                { key: ["group","name"], label: "Группа", width: "160px" },
                { key: "url", label: "Ссылка" },
                { key: "actions", label: "", width: "175px" },
            ],
        };
    },
    computed: {
        divisions() {
            const divisions = usePage().props.divisions;
            return divisions;
        },
        current_user: () => usePage().props.current_user.data,

        filteredDivisions() {
            const search = this.search.toLowerCase().trim();

            if (!search) {
                return this.divisions;
            }

            return {
                ...this.divisions,
                data: this.divisions.data.filter(division =>
                    division.name.toLowerCase().includes(search)
                ),
            };
        },
    },
};
</script>

<template>
    <Table :data="filteredDivisions" :columns="columns" header="Подразделения">
        <template #toolbar-left>
            <input v-model="search" type="text" placeholder="поиск.." class="search-input"/>
        </template>

        <template #toolbar-right v-if="current_user.roles[0]?.role.code === 'admin'">
            <AddButton href="/divisions/create" />
        </template>

        <template #actions="{ row }">
            <EditButton v-if="current_user.roles[0]?.role.code === 'admin'" :href="route('divisions.edit', row)" class="w-full"/>

            <GoToButton :href="route('divisions.show', row.id)" class="w-full"/>

            <DeleteButton v-if="row.userCount === 0" :href="route('divisions.destroy', row)" class="w-full" />
        </template>
    </Table>
</template>

<style lang="sass">
.w-full
    width: 100%

.search-input
    width: 260px
</style>

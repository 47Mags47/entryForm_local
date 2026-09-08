<script>
import { usePage, router } from "@inertiajs/vue3";
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
            timeout: null,

            columns: [
                { key: "name", label: "Наименовение" },
                { key: "actions", label: "" },
            ],
        };
    },

    watch: {
        search() {
            clearTimeout(this.timeout)

            this.timeout = setTimeout(() => {
                router.get(route('cities.index'),
                    {
                        filter: {
                            city: this.search
                        }
                    },
                    {
                        preserveState: true,
                    }
                )
            }, 500)
        }
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
    <Table :data="cities" :columns="columns" header="Города">
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

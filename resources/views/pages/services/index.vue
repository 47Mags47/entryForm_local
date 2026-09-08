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
                { key: "name", label: "Наименование" },
                { key: "duration", label: "Время", width: "100px" },
                { key: "actions", label: "", width: "118px" },
            ],
        };
    },

    watch: {
        search() {
            clearTimeout(this.timeout)

            this.timeout = setTimeout(() => {
                router.get(route('services.index'),
                    {
                        filter: {
                            service: this.search
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
        services() {
           return usePage().props.services;
        },
    },
};
</script>

<template>
    <Table :data="services" :columns="columns" header="Услуга">
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

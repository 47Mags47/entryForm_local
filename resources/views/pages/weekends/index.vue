<script>
import {
    Table,
    BlueButton, AddButton, DeleteButton, EditButton
 } from "@components";
import { router, usePage } from "@inertiajs/vue3";

export default {
    components: {
        Table,
        BlueButton, AddButton, DeleteButton, EditButton,
    },

    data() {
        return {
            columns: [
                {
                    label: 'Начальная дата',
                    key:   'date_start'
                },
                {
                    label: 'Конечная дата',
                    key:   'date_end'
                },
                { key: "actions", label: "" },
            ]
        }
    },

    computed: {
        division:       () => usePage().props.current_division.data,
        worker:         () => usePage().props.worker.data,
        weekends:       () => usePage().props.weekends
    }
};
</script>

<template>
    <Table
        :data="weekends"
        :columns="columns"
        :header="`Отпуска ${worker.last_name} ${worker.first_name?.charAt(0) + '.'}${worker.middle_name?.charAt(0) + '.'}`"
    >
        <template #toolbar-right>
            <AddButton :href="route('weekends.create', { division: division.id, user: worker.id })" />
        </template>

        <template #actions="{ row }">
            <EditButton
                :href="route('weekends.edit', { division: division.id, user: worker.id, weekend: row.id })"
                class="w-full"
            />

            <DeleteButton
                :href="route('weekends.destroy', { division: division.id, user: worker.id, weekend: row.id })"
                class="w-full"
            />
        </template>
    </Table>
</template>

<style lang="sass" scoped>
.w-full
    width: 100%
</style>

<script>
import {
    Table,
    BlueButton, AddButton, DeleteButton, EditButton
 } from "@components";
import { DivisionTab } from "@includes";
import { usePage } from "@inertiajs/vue3";

export default {
    components: {
        DivisionTab,
        Table,
        BlueButton, AddButton, DeleteButton, EditButton,
    },

    data() {
        return {
            columns: [
                {
                    type: 'date',
                    label: 'Начало',
                    key:   'date_start'
                },
                {
                    type: 'date',
                    label: 'Конец',
                    key:   'date_end'
                },
                { key: 'actions', label: '' },
            ]
        }
    },

    computed: {
        division:            () => usePage().props.current_division.data,
        unavailable_periods: () => usePage().props.unavailable_periods
    }
};
</script>

<template>
    <DivisionTab current="unavailable-periods">
        <Table
            :data="unavailable_periods"
            :columns="columns"
            :header="`Нерабочие дни ${division.name}`"
        >
            <template #toolbar-right>
                <AddButton :href="route('unavailable-periods.create', { division: division.id })" />
            </template>

            <template #actions="{ row }">
                <EditButton
                    :href="route('unavailable-periods.edit', { division: division.id, unavailable_period: row.id })"
                    class="w-full"
                />

                <DeleteButton
                    :href="route('unavailable-periods.destroy', { division: division.id, unavailable_period: row.id })"
                    class="w-full"
                />
            </template>
        </Table>
    </DivisionTab>
</template>

<style lang="sass" scoped>
.w-full
    width: 100%
</style>

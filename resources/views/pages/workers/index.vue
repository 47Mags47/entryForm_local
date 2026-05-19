<script setup>
import { AuthenticatedLayout } from "@layouts";
import { DivisionTab } from "@includes";
import { Table, AddButton, DeleteButton, EditButton } from "@components";
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const worker = computed(() => usePage().props.users);

const division = usePage().props.division.data;

const columns = [
    {
        label: "ФИО",
        render: (row) => {
            const lastName = row.last_name || "";
            const firstNameInitial = row.first_name
                ? row.first_name[0] + "."
                : "";
            const middleNameInitial = row.middle_name
                ? row.middle_name[0] + "."
                : "";

            const result = [lastName, firstNameInitial, middleNameInitial]
                .filter((part) => part !== "")
                .join(" ");

            return result || "-";
        },
    },
    { key: "email", label: "Email" },
    { key: "actions", label: "" },
];
</script>

<template>
    <AuthenticatedLayout>
        <DivisionTab current="workers">
            <Table :data="worker" :columns="columns">
                <template #toolbar-right>
                    <AddButton
                        :href="
                            route('invites.create', {
                                division_id: division.id,
                            })
                        "
                    />
                </template>

                <template #actions="{ row }">
                    <EditButton
                        :href="route('workers.edit', { worker: row.id })"
                    />
                    <DeleteButton
                        :href="
                            route('workers.destroy', {
                                division: division.id,
                                worker: row.id,
                            })
                        "
                    />
                </template>
            </Table>
        </DivisionTab>
    </AuthenticatedLayout>
</template>

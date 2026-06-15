<script setup>
import { AuthenticatedLayout } from "@layouts";
import { DivisionTab } from "@includes";
import { Table,
    AddButton, DeleteButton, EditButton, BlueButton,
    CheckBox, PenIco } from "@components";
import { usePage, router } from "@inertiajs/vue3";

import { ref, computed } from "vue";

const worker = computed(() => usePage().props.users);
const current_user = computed(() => usePage().props.current_user.data);
const division = usePage().props.division.data;

let isAdminEdit = ref(false);

let loadingUserid = null

const toggleCheckbox = async (row, val) => {
    if (row.role.code === 'admin') return

    loadingUserid = row.id
    const previousRole = row.role.code;
    row.role.code = val ? "division_admin" : "division_worker";
    try {
        if (val) {
            await router.post(
                route("division-admins.store", {
                    division: division.id,
                }),
                { user_id: row.id },
            );
        } else {
            await router.delete(
                route("division-admins.destroy", {
                    division: division.id,
                    division_admin: row.id,
                }),
            );
        }
    } catch (error) {
        console.error(error);
        row.role.code = previousRole;
    } finally {
        loadingUserid = null
    }
};

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
    { key: "email", label: "Email"  },
    {
        label: 'Роль',
        render: (row) => {
            return loadingUserid === row.id ? 'загрузка..' : row.role.name
        }
    },
    { key: "actions", label: ""     },
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
                    <BlueButton v-if="current_user.role.code !== 'division_worker'" @click="isAdminEdit = !isAdminEdit">
                        <PenIco />
                    </BlueButton>
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
                    <CheckBox
                        v-show="isAdminEdit && row.role.code !== 'admin' && current_user.id !== row.id"
                        :modelValue="row.role.code === 'division_admin'"
                        @update:modelValue="(val) => toggleCheckbox(row, val)"
                    />
                </template>
            </Table>
        </DivisionTab>
    </AuthenticatedLayout>
</template>

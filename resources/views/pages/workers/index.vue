<script setup>
import { AuthenticatedLayout } from "@layouts";
import { DivisionTab } from "@includes";
import { Table,
    AddButton, DeleteButton, EditButton, BlueButton,
    CheckBox, Select,
    PenIco, PersonIco } from "@components";
import { usePage, router } from "@inertiajs/vue3";

import { h } from "vue";

import { ref, computed } from "vue";

const worker = computed(() => usePage().props.users);
const current_user = computed(() => usePage().props.current_user.data);
const division = usePage().props.division.data;

let isAdminEdit = ref(false);

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
        component: (row) => {
            return h(Select, {
                disabled: !isAdminEdit.value || row.role.code === 'admin',
                modelValue: row.role.code,
                hasSearch: false,
                options: [
                    { label: 'Администратор системы', value: 'admin' },
                    { label: 'Администратор организации', value: 'division_admin' },
                    { label: 'Работник организации', value: 'division_worker' }
                ],
                'onUpdate:modelValue': (value) => {
                    if (row.role.code === 'admin' || value === 'admin') return

                    row.role.code = value ?? row.role.code;

                    router.post(
                        route("division-admins.store", {
                            division: division.id,
                        }),
                        {
                            user_id: row.id,
                            role_code: row.role.code
                        },
                    );
                }
            });
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
                    <div class="container-actions-row">
                        <BlueButton
                            v-if="current_user.role.code === 'admin' && row.role.code !== 'admin'"
                            @click="router.get(route('user.edit', { user: row.id }))">
                            <PersonIco />
                        </BlueButton>
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
                    </div>
                </template>
            </Table>
        </DivisionTab>
    </AuthenticatedLayout>
</template>

<style lang="sass" scoped>
.container-actions-row
    width: 100%
    display: flex
    justify-content: end
    gap: 10px
</style>

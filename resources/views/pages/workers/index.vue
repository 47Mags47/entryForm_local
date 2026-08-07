<script>
import { h } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { DivisionTab } from "@includes";
import { AuthenticatedLayout } from "@layouts";
import {
    Table,

    Select,
    BlueButton,
    AddButton,
    EditButton,
    DeleteButton,

    PenIco,
    PersonIco,
    RestoreIco
} from "@components";

export default {
    components: {
        AuthenticatedLayout,
        Table,
        DivisionTab,

        Select,
        BlueButton,
        AddButton,
        EditButton,
        DeleteButton,

        PenIco,
        PersonIco,
        RestoreIco
    },
    computed: {
        users: () => usePage().props.users,
        division: () => usePage().props.current_division.data,
        current_user: () => usePage().props.current_user.data,

    },
    data() {
        return {
            router,
            isAdminEdit: false,
            columns: [
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
                {
                    label: 'Роль',
                    component: (user) => {
                        return h(Select, {
                            disabled: !(this.isAdminEdit && user.id !== this.current_user.id && user.deleted_at === null && user.role.code !== 'admin'),

                            modelValue: user.role.code,
                            hasSearch: false,
                            options: [
                                user.role.code === 'admin' ? { label: 'Администратор системы', value: 'admin' } : null,
                                { label: 'Администратор организации', value: 'division_admin' },
                                { label: 'Работник организации', value: 'division_worker' }
                            ].filter(Boolean),
                            'onUpdate:modelValue': (value) => {
                                if (user.role.code === 'admin' || value === 'admin') return

                                router.post(
                                    route("division-admins.store", {
                                        division: this.division.id,
                                    }),
                                    {
                                        user_id: user.id,
                                        role_code: value
                                    },
                                );
                            }
                        });
                    }
                },
                { key: "actions", label: "" },
            ]
        }
    },
    methods: {
        getRowColor(row) {
            if (row.deleted_at !== null)
                return 'deleted-row'
        },
        getUserRole(user) {
            return user.roles.find(role => role.division.id === this.division.id)?.role ?? user.roles[0].role
        }
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <DivisionTab current="workers">
            <Table :data="users" :row-class="getRowColor" :columns="columns">
                <template #toolbar-right>
                    <AddButton :href="route('invites.create', {
                        division: division.id,
                    })" />
                    <BlueButton v-if="getUserRole(current_user).code !== 'division_worker'"
                        @click="() => isAdminEdit = !isAdminEdit">
                        <PenIco />
                    </BlueButton>
                </template>

                <template #actions="{ row }">
                    <div class="container-actions-row">
                        <BlueButton v-if="getUserRole(current_user).code === 'admin' && row.role.code !== 'admin' && row.deleted_at === null"
                            @click="router.get(route('user.edit', { user: row.id }))">
                            <PersonIco />
                        </BlueButton>

                        <EditButton v-if="row.deleted_at === null"
                            :href="route('workers.edit', { worker: row.id, division: division.id })" />

                        <DeleteButton v-if="row.deleted_at === null" :href="route('workers.destroy', {
                            division: division.id,
                            worker: row.id,
                        })
                            " />
                        <BlueButton class="w-full" v-else
                            @click="router.get(route('workers.restore', { worker: row.id, division: division.id }))">
                            <RestoreIco />
                        </BlueButton>
                    </div>
                </template>
            </Table>
        </DivisionTab>
    </AuthenticatedLayout>
</template>

<style lang="sass">
.container-actions-row
    width: 100%
    display: flex
    justify-content: end
    gap: 10px

.deleted-row
    background: #ffe3e3

.w-full
    width: 100%
</style>

<script>
import { ref, watch } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { AuthenticatedLayout } from "@layouts";
import { Table, CheckBox, GoToButton } from "@components";
import { DivisionTab } from "@includes";

export default {
    components: {
        AuthenticatedLayout,
        Table,
        CheckBox,
        GoToButton,
        DivisionTab,
    },

    setup() {
        const page = usePage();
        const division = ref(page.props.division.data);
        const admins = ref(page.props.users);

        watch(
            () => page.props.users,
            (newUsers) => {
                admins.value = newUsers;
            },
        );

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

                    const result = [
                        lastName,
                        firstNameInitial,
                        middleNameInitial,
                    ]
                        .filter((part) => part !== "")
                        .join(" ");

                    return result || "-";
                },
            },
            { key: "email", label: "Email" },
            { key: "actions", label: "" },
        ];

        const toggleCheckbox = async (row, val) => {
            const previousRole = row.role.code;

            row.role.code = val ? "division_admin" : "division_worker";

            try {
                if (val) {
                    await router.post(
                        route("division-admins.store", {
                            division: division.value.id,
                        }),
                        { user_id: row.id },
                    );
                } else {
                    await router.delete(
                        route("division-admins.destroy", {
                            division: division.value.id,
                            division_admin: row.id,
                        }),
                    );
                }
            } catch (error) {
                console.error(error);
                row.role.code = previousRole;
            }
        };

        return {
            admins,
            division,
            columns,
            toggleCheckbox,
        };
    },
};
</script>

<template>
    <AuthenticatedLayout>
        <DivisionTab :division_id="division.id">
            <Table :data="admins" :columns="columns">
                <template #toolbar-right>
                    <GoToButton
                        :href="
                            route('division-admins.index', {
                                division: division.id,
                            })
                        "
                    />
                </template>

                <template #actions="{ row }">
                    <CheckBox
                        :modelValue="row.role.code === 'division_admin'"
                        @update:modelValue="(val) => toggleCheckbox(row, val)"
                    />
                </template>
            </Table>
        </DivisionTab>
    </AuthenticatedLayout>
</template>

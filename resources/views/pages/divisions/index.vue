<script>
import { usePage, router } from "@inertiajs/vue3";
import {
    Table,
    EditButton, DeleteButton, AddButton, GoToButton, BlueButton,
    CheckBox,
    RestoreIco
} from "@components";

export default {
    components: {
        Table,
        EditButton, DeleteButton, BlueButton, AddButton, GoToButton,
        CheckBox,
        RestoreIco
    },

    data() {
        return {
            router,
            isShowDivisionsDeleted: usePage().props.trashed,
            search: '',
            timeout: null,

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

    watch: {
        search() {
            clearTimeout(this.timeout)

            this.timeout = setTimeout(() => {
                router.get(route('divisions.index'),
                    {
                        filter: {
                            division: this.search
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
        divisions() {
            const divisions = usePage().props.divisions;
            return divisions;
        },
        current_user: () => usePage().props.current_user.data,
    },

    methods: {
        getRowColor(row) {
            if (row.deleted_at !== null)
                return 'deleted-row'
        },

        checkboxHandler(flag) {
            this.isShowDivisionsDeleted = flag

            router.get(route('divisions.index', {
                trashed: this.isShowDivisionsDeleted
            }))
        }
    }
};
</script>

<template>
    <Table :data="divisions" :row-class="getRowColor" :columns="columns" header="Подразделения">
        <template #toolbar-left>
            <div class="divisions-toolbar-left-wrapper">
                <input v-model="search" type="text" placeholder="поиск.." class="search-input"/>
                <CheckBox
                    label="показать удалённые"
                    :modelValue="isShowDivisionsDeleted"
                    @update:modelValue="checkboxHandler"
                />
            </div>
        </template>

        <template #toolbar-right v-if="current_user.roles[0]?.role.code === 'admin'">
            <AddButton href="/divisions/create" />
        </template>

        <template #actions="{ row }">
            <EditButton v-if="current_user.roles[0]?.role.code === 'admin' && row.deleted_at === null" :href="route('divisions.edit', row)" class="w-full"/>

            <GoToButton v-if="row.deleted_at === null" :href="route('divisions.show', row.id)" class="w-full"/>

            <DeleteButton
                v-if="row.deleted_at === null && current_user.roles[0]?.role.code === 'admin' && row.userCount === 0"
                :href="route('divisions.destroy', row)" class="w-full"
            />
            <BlueButton class="w-full" v-else-if="row.deleted_at !== null"
                @click="router.get(route('divisions.restore', row.id ))">
                <RestoreIco />
            </BlueButton>
        </template>
    </Table>
</template>

<style lang="sass" scoped>
.w-full
    width: 100%

:deep(.deleted-row)
    background: #ffe3e3

.divisions-toolbar-left-wrapper
    display: flex
    gap: 5px
    align-items: center

    width: 100%


.search-input
    width: 260px
</style>

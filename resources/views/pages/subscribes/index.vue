<script>
import { usePage, useForm, router } from "@inertiajs/vue3";
import { DivisionTab } from "@includes";
import { Table, GoToButton, AddButton, DeleteButton, BlueButton, DatePicker, DownloadIco, Select, StringInput } from "@components";
import { DateTime } from "luxon";

export default {
    components: {
        DivisionTab,
        Table,
        GoToButton,
        DeleteButton,
        AddButton,
        BlueButton,
        DatePicker,
        DownloadIco,
        Select, StringInput
    },

    data() {
        return {
            selectedDate: null,
            startDate: { from: DateTime.now().startOf('month'), to: null },
            search: '',

            form: useForm({
                from: DateTime.now().startOf('month').toFormat('yyyy-MM-dd'),
                to: null,
                worker_id: '',
                service_id: '',
            }),
        }
    },

    computed: {
        current_user:   () => usePage().props.current_user.data,
        division:       () => usePage().props.division.data,
        subscribes:     () => usePage().props.subscribes,
        workers: () => (usePage().props.workers?.data ?? []).map(worker => ({
                value: worker.id,
                label: worker.last_name + ' ' + worker.first_name?.charAt(0).toUpperCase() + '.' + worker.middle_name?.charAt(0).toUpperCase() + '.',
            })),
        services: () => (usePage().props.services?.data ?? []).map(service => ({
                value: service.id,
                label: service.name
            })),

        columns() {
            return [
                { key: "last_name", label: "Фамилия", width: "150px" },
                { key: "first_name", label: "Имя", width: "150px" },
                { key: "middle_name", label: "Отчество", width: "150px" },
                { key: ["service", "name"], label: "Услуга" },
                {
                    key: "start_at",
                    label: "Дата записи",
                    splitDateTime: false,
                    width: "180px",
                },
                {
                    label: "Специалист",
                    width: "210px",
                    class: (row) => {
                        if (row.worker.deleted_at !== null)
                            return 'deleted-worker'
                    },
                    render: (row) => {
                        if (row.worker.deleted_at !== null)
                            return `${row.worker.name} (удалён)`
                        return row.worker.name
                    }
                },
                { key: "actions", label: "", width: "60px" },
            ];
        },

        filteredSubscribes() {
            const filtered = this.search.toLowerCase().trim();

            if (!filtered) {
                return this.subscribes;
            }

            return {
                ...this.subscribes,
                data: this.subscribes.data.filter(subscribe => {
                        if (subscribe.first_name?.toLowerCase().includes(filtered))
                            return true
                        if (subscribe.last_name?.toLowerCase().includes(filtered))
                            return true
                        if (subscribe.middle_name?.toLowerCase().includes(filtered))
                            return true
                    }
                ),
            };
        },
    },

    methods: {
        hasDelete(subscribe) {
            if (this.getUserRole(this.current_user).code === "admin")
                return true

            if (this.getUserRole(this.current_user).code === "division_admin")
                return true

            if (this.getUserRole(this.current_user).code === "division_worker")
                return subscribe.worker.id === this.current_user.id
        },
        updateDateBetween(newDateBetween) {
            this.form.from   =   newDateBetween.from?.toFormat('yyyy-MM-dd')
            this.form.to     =   newDateBetween.to?.toFormat('yyyy-MM-dd')
        },

        applyFilters() {
            this.form
                .transform(data => ({
                    ...data,
                    from: data.from,
                    to: data.to,
                }))
                .get(route('subscribes.index', {
                    division: this.division.id,
                }), {
                    preserveState: true,
                    preserveScroll: true,
                });
        },
        resetData() {
            router.get(route('subscribes.index', {division: this.division.id}))
        },

        subscribesExport() {
            window.open(route('subscribes.export', { division: this.division.id, from: this.form.from, to: this.form.to }))
        },

        getRowColor(row) {
            if (row.deleted_at !== null)
                return 'deleted-row'
        },

        getUserRole(user) {
            return user.roles.find(role => role.division.id === this.division.id)?.role ?? user.roles[0].role
        }
    },
};
</script>

<template>
    <DivisionTab current="subscribes">
        <Table :data="filteredSubscribes" :columns="columns" :row-class="getRowColor" header="Список обращений">
            <template #toolbar-left>
                <div class="filters-wrapper">
                    <DatePicker
                        :isRange="true"
                        name="date"
                        :value="startDate"
                        :showAvailable="false"
                        @update:value="updateDateBetween"
                    />
                    <!-- HACK select не рос в высоту -->
                    <Select class="filter-item" :options="workers" name="workers" v-model="form.worker_id" :has-search="false" placeholder="Специалист"/>
                    <Select class="filter-item" :options="services" name="services" v-model="form.service_id" :has-search="false" placeholder="Услуга"/>
                    <StringInput @update:value="(val) => search = val" :value="form.search" name="search" placeholder="ФИО заявителя" />
                </div>
                <BlueButton :handle-click="applyFilters"> применить </BlueButton>
                <BlueButton :handle-click="resetData"> сбросить </BlueButton>
            </template>
            <template #toolbar-right>
                <BlueButton :handle-click="subscribesExport">
                    <DownloadIco/>
                </BlueButton>
                <AddButton :href="route('subscribes.create', { division: division.id })" />
            </template>
            <template #actions="{ row }">
                <DeleteButton v-if="hasDelete(row)" :href="route('subscribes.destroy', {
                    division: division.id,
                    subscribe: row.id,
                })" />
                <GoToButton :href="route('subscribes.show', {
                    division: division.id,
                    subscribe: row.id,
                })
                    " />
            </template>
        </Table>
    </DivisionTab>
</template>

<style lang="sass">
.toolbar-left
    gap: 10px
    flex-wrap: wrap

    .date-picker-button
        background: blue


.deleted-row
    background: #ffe3e3

.deleted-worker
    color: red

.filters-wrapper
    display: flex
    flex-wrap: wrap
    gap: 10px
    .filter-item
        width: 300px


</style>

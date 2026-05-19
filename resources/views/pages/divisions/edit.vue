<script>
import { usePage, useForm } from "@inertiajs/vue3";

import { AuthenticatedLayout } from "@layouts";

import {
    HorizontalForm,
    FormGroup,
    Select,
    WorkSchedule,
    TextArea,
    StringInput,
} from "@components";

export default {
    components: {
        AuthenticatedLayout,
        HorizontalForm,
        FormGroup,
        Select,
        WorkSchedule,
        TextArea,
        StringInput,
    },

    data() {
        const division = usePage().props.division.data;
        const division_group = usePage().props.division_group;
        const cities = usePage().props.cities;
        return {
            form: useForm({
                name: division.name,
                address: division.address,
                city_id: division.city.id,
                shedules: division.shedules,
                group_id: division.group.id,
                url: division.url,
            }),
            cities,
            division,
            division_group,
        };
    },

    computed: {
        cityOptions() {
            return this.cities.map((city) => ({
                value: city.id,
                label: city.name,
            }));
        },
       GroupOption() {
            return this.division_group.map((group) => ({
                value: group.id,
                label: group.name,
            }));
        },
    },

    methods: {
        onSubmit(e) {
            e.preventDefault();
            this.form.put(
                route("divisions.update", { division: this.division.id }),
            );
        },
    },
};
</script>

<template>
    <AuthenticatedLayout>
        <HorizontalForm
            header="Организации"
            sbm="Сохранить"
            :handleSubmit="onSubmit"
        >
            <FormGroup name="organization" label="Информация об организации">
                <TextArea
                    label="Наименование"
                    name="name"
                    :value="form.name"
                    @update:value="(val) => (form.name = val)"
                    :rows="4"
                />
                <TextArea
                    label="Адрес"
                    name="adres"
                    :value="form.address"
                    @update:value="(val) => (form.address = val)"
                    autocomplete="current-adres"
                    :rows="6"
                />
                <Select
                    label="Город"
                    name="city_id"
                    v-model="form.city_id"
                    :options="cityOptions"
                    placeholder="Выберите город"
                />
                <Select
                    label="Группа"
                    name="group_id"
                    v-model="form.group_id"
                    :options="GroupOption"
                    placeholder="Выберите группу подразделение"
                />
                <StringInput
                    label="Ссылка"
                    name="url"
                    :value="form.url"
                    placeholder="http://example.ru"
                    @update:value="(val) => (form.url = val)"
                    autocomplete="url"
                />
            </FormGroup>
            <FormGroup name="work" label="График работы">
                <WorkSchedule v-model="form.shedules" name="shedules" />
            </FormGroup>
        </HorizontalForm>
    </AuthenticatedLayout>
</template>

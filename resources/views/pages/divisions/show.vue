<script>
import { usePage, useForm } from "@inertiajs/vue3";

import { AuthenticatedLayout } from "@layouts";
import { DivisionTab } from "@includes";
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
        DivisionTab,
        HorizontalForm,
        FormGroup,
        Select,
        WorkSchedule,
        TextArea,
        StringInput,
    },

    data() {
        const division = usePage().props.division.data;
        const cities = usePage().props.cities;
        return {
            form: useForm({
                name: division.name,
                address: division.address,
                city_id: division.city.id,
                group_name: division.group.name,
                url: division.url,
                shedules: division.shedules,
            }),
            cities,
            division,
            isEditing: false,
        };
    },
    computed: {
        cityOptions() {
            return this.cities.map((city) => ({
                value: city.id,
                label: city.name,
            }));
        },
    },
    methods: {
        onSubmit(e) {
            e.preventDefault();
            if (!this.isEditing) {
                this.isEditing = true;
                return;
            }

            this.form.put(
                route("divisions.update", { division: this.division.id }),
                {
                    onSuccess: () => {
                        this.isEditing = false;
                    },
                },
            );
        },
    },
};
</script>

<template>
    <AuthenticatedLayout>
        <DivisionTab current="info">
            <HorizontalForm
                header="Общая информация"
                :sbm="isEditing ? 'Сохранить' : 'Редактировать'"
                :handleSubmit="onSubmit"
            >
                <FormGroup
                    name="organization"
                    label="Информация об организации"
                >
                    <TextArea
                        label="Наименование"
                        name="name"
                        :value="form.name"
                        @update:value="(val) => (form.name = val)"
                        :rows="5"
                        :disabled="!isEditing"
                    />
                    <TextArea
                        label="Адрес"
                        name="address"
                        :value="form.address"
                        @update:value="(val) => (form.address = val)"
                        autocomplete="current-address"
                        :rows="3"
                        :disabled="!isEditing"
                    />
                    <Select
                        label="Город"
                        name="city_id"
                        v-model="form.city_id"
                        :options="cityOptions"
                        placeholder="Выберите город"
                        :disabled="!isEditing"
                    />
                    <StringInput
                        label="Группа"
                        name="group_name"
                        :value="form.group_name"
                        disabled
                    />
                    <StringInput
                        label="Ссылка"
                        name="url"
                        :value="form.url"
                        @update:value="(val) => (form.url = val)"
                        autocomplete="url"
                        :disabled="!isEditing"
                    />
                </FormGroup>

                <FormGroup name="work" label="График работы">
                    <WorkSchedule
                        v-model="form.shedules"
                        name="shedules"
                        :disabled="!isEditing"
                    />
                </FormGroup>
            </HorizontalForm>
        </DivisionTab>
    </AuthenticatedLayout>
</template>

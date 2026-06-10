<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { DivisionTab } from "@includes";
import { AuthenticatedLayout } from "@layouts";
import {
    HorizontalForm,
    WorkSchedule,
    FormGroup,
    StringInput,
    Table,
    CheckBox,

} from "@components";
import List from "../../components/list/List.vue";

const worker = usePage().props.worker.data;
const division = usePage().props.division.data;
const services = usePage().props.services;

const form = useForm({
    shedules: worker.shedules,
    name: worker.last_name + ' ' + worker.first_name[0]+ '. ' + worker.middle_name[0] + '.',
    email: worker.email,
    service_ids: worker.services.map((service) => service.id),
    division_id: division.id,
});

function toggleCheckbox(row, val) {
    console.log(row, val)
    if (val) form.service_ids.push(row.id);
    else form.service_ids = form.service_ids.filter((el) => el !== row.id);
}

const columns = [
    { key: "name", label: "", width: "200px" },
    { key: "actions", label: "" },
];

function onSubmit(e) {
    e.preventDefault();

    form.put(route("workers.update", { worker: worker.id }));
}
</script>

<template>
    <AuthenticatedLayout>
        <DivisionTab current="workers">
            <HorizontalForm
                header="Информация о работнике"
                sbm="Сохранить"
                :handleSubmit="onSubmit"
            >
                <FormGroup name="info" label="Информация">
                    <StringInput
                        label="ФИО"
                        name="name"
                        :value="form.name"
                        disabled
                    />
                    <StringInput
                        label="Email"
                        name="email"
                        :value="form.email"
                        disabled
                    />
                </FormGroup>

                <FormGroup name="work" label="График работы">
                    <WorkSchedule
                        header="График работы"
                        v-model="form.shedules"
                        name="shedules"
                    />
                </FormGroup>
                <FormGroup id="services" name="services" label="Услуги">
                     <div
                        class="services-row"
                        v-for="(service, index) in services"
                        :key="service.id"
                        @click="toggleCheckbox( service, !form.service_ids.includes(service.id))"
                     >
                        <div class="service-row-checkbox">
                            <CheckBox :modelValue="form.service_ids.includes(service.id)" />
                        </div>
                        <div class="service-row-name">
                            <span> {{ service.name }} </span>
                        </div>
                     </div>
                </FormGroup>
            </HorizontalForm>
        </DivisionTab>
    </AuthenticatedLayout>
</template>

<style lang="sass" scoped>
#services
    :deep(.services)
        padding: 0
        overflow-y: auto
        max-height: 524px
        @include scroll
    :deep(.form-group)
        width: 400px
        justify-content: start

    .services-row
        display: flex
        align-items: center
        gap: 10px
        cursor: pointer
        padding: 10px
        &:hover
            background: #ddd
</style>

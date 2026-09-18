<script setup>
import { ref } from "vue";
import { useForm, usePage, router } from "@inertiajs/vue3";
import { DivisionTab } from "@includes";
import {
    HorizontalForm,
    WorkSchedule,
    FormGroup,
    StringInput,
    CheckBox,
    BlueButton,
} from "@components";

const worker = usePage().props.worker.data;
const division = usePage().props.current_division.data;
const services = usePage().props.services;
const isSubscribe = ref(worker.is_subscribe_available);

const form = useForm({
    shedules: worker.shedules,
    name: worker.last_name + ' ' + worker.first_name[0]+ '. ' + worker.middle_name[0] + '.',
    email: worker.email,
    service_ids: worker.services.map((service) => service.id),
    division_id: division.id,
});

function toggleCheckbox(row, val) {
    if (val) form.service_ids.push(row.id);
    else form.service_ids = form.service_ids.filter((el) => el !== row.id);
}

function onSubmit(e) {
    e.preventDefault();

    form.put(route("workers.update", { worker: worker.id, isSubscribeAvailable: isSubscribe.value, division: division.id }));
}

function routeToWeekendsPage() {
    router.get(route('weekends.index', {
        division:   division.id,
        worker:     worker.id,
    }));
}
</script>

<template>
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
                <div class="mt-10">
                    <CheckBox
                        label="доступна запись"
                        :modelValue="isSubscribe"
                        @update:modelValue="(val) => isSubscribe = !isSubscribe"
                    />
                </div>
                <BlueButton class="mt-10" @click="routeToWeekendsPage">
                    <span> Отпуска </span>
                </BlueButton>
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

.mt-10
    margin-top: 10px
</style>

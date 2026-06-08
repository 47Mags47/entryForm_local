<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { GuestLayout } from "@layouts";
import { VerticalForm, StringInput, NumberInput } from "@components";

const invite = usePage().props.invite.data
const form = useForm({
    token: invite.token,
    first_name: "",
    last_name: "",
    middle_name: "",
    email: invite.email,
    phone: "",
    office: '',
    password: "",
    password_confirmation: "",
});

function onSubmit(e) {
    e.preventDefault();


    form.post(route("workers.store"));
}
</script>

<template>
    <GuestLayout>
        <VerticalForm
            header="Регистрация"
            sbm="Зарегистрироваться"
            :handleSubmit="onSubmit"
        >
            <StringInput
                label="Фамилия"
                name="last_name"
                :value="form.last_name"
                @update:value="(val) => (form.last_name = val)"
                autocomplete="last_name"
            />
             <StringInput
                label="Имя"
                name="first_name"
                :value="form.first_name"
                @update:value="(val) => (form.first_name = val)"
                autocomplete="first_name"
            />
             <StringInput
                label="Отчество(при наличии)"
                name="middle_name"
                :value="form.middle_name"
                @update:value="(val) => (form.middle_name = val)"
                autocomplete="middle_name"
            />
            <StringInput
                label="Email"
                type="email"
                name="email"
                :value="form.email"
                @update:value="(val) => (form.email = val)"
                autocomplete="email"
            />
            <NumberInput
                label="Телефон"
                name="phone"
                placeholder="+7(___) ___-__-__"
                :value="form.phone"
                @update:value="(val) => (form.phone = val)"
                autocomplete="phone"
            />
            <StringInput
                label="Кабинет"
                type="text"
                name="office"
                :value="form.office"
                @update:value="(val) => (form.office = val)"
                autocomplete="office"
            />
            <StringInput
                label="Пароль"
                type="password"
                name="password"
                :value="form.password"
                @update:value="(val) => (form.password = val)"
                autocomplete="new-password"
            />
            <StringInput
                label="Подтвердите пароль"
                type="password"
                name="password_confirmation"
                :value="form.password_confirmation"
                @update:value="(val) => (form.password_confirmation = val)"
                autocomplete="new-password"
            />
        </VerticalForm>
    </GuestLayout>
</template>

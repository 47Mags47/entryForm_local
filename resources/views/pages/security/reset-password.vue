<script>
import { useForm, usePage } from "@inertiajs/vue3";
import { GuestLayout } from "@layouts";
import { VerticalForm, StringInput } from "@components";

export default {
    components: {
        GuestLayout,
        VerticalForm,
        StringInput,
    },

    data() {
        const token = usePage().props.token;
        const email = usePage().props.email;
        return {
            form: useForm({
                token: token,
                email: email,
                password: "",
                password_confirmation: "",
            }),
        };
    },

    methods: {
        onSubmit(e) {
            e.preventDefault();
            this.form.post(route("password.update"));
        },
    },
};
</script>

<template>
    <GuestLayout>
        <VerticalForm
            header="Сбросить пароль"
            sbm="Сбросить"
            :handleSubmit="onSubmit"
        >
            <StringInput
                label="Пароль"
                type="password"
                name="password"
                :value="form.password"
                @update:value="(val) => (form.password = val)"
                autocomplete="password"
            />
            <StringInput
                label="Повторите пароль"
                type="password"
                name="password_confirmation"
                :value="form.password_confirmation"
                @update:value="(val) => (form.password_confirmation = val)"
                autocomplete="password_confirmation"
            />
        </VerticalForm>
    </GuestLayout>
</template>

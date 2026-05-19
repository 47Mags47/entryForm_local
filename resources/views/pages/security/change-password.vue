<script>
import { AuthenticatedLayout } from "@layouts";
import { VerticalForm, StringInput } from "@components";
import { useForm, usePage } from "@inertiajs/vue3";

export default {
    components: {
        AuthenticatedLayout,
        VerticalForm,
        StringInput,
    },
    data() {
        const current_user = usePage().props.current_user.data;
        return {
            form: useForm({
                old_password: "",
                password: "",
                password_confirmation: "",
            }),
            current_user,
        };
    },
    methods: {
        onSubmit(e) {
            e.preventDefault();

            this.form.put(
                route("passwordChange.update", { user: this.current_user.id }),
            );
        },
    },
};
</script>

<template>
    <AuthenticatedLayout>
        <VerticalForm
            header="Смена пароля"
            sbm="Сменить"
            :handleSubmit="onSubmit"
        >
            <StringInput
                label="Старый пароль"
                name="old_password"
                type="password"
                :value="form.old_password"
                @update:value="(val) => (form.old_password = val)"
            />
            <StringInput
                label="Новый пароль"
                name="password"
                type="password"
                :value="form.password"
                @update:value="(val) => (form.password = val)"
            />
            <StringInput
                label="Повторите пароль"
                name="password_confirmation"
                type="password"
                :value="form.password_confirmation"
                @update:value="(val) => (form.password_confirmation = val)"
            />
        </VerticalForm>
    </AuthenticatedLayout>
</template>

<script>
import { router, useForm, usePage } from "@inertiajs/vue3";
import { AuthenticatedLayout } from "@layouts";
import { VerticalForm, StringInput, BlueButton } from "@components";

export default {
    components: {
        AuthenticatedLayout,
        VerticalForm,
        StringInput,
        BlueButton,
    },
    data() {
        const current_user = usePage().props.current_user.data;
        return {
            form: useForm({
                first_name: current_user.first_name,
                middle_name: current_user.middle_name,
                last_name: current_user.last_name,
            }),
            current_user,
        };
    },
    methods: {
        onSubmit(e) {
            e.preventDefault();

            this.form.put(route("user.update", { user: this.current_user.id }));
        },
    },
};
</script>

<template>
    <AuthenticatedLayout>
        <VerticalForm
            header="Личный кабинет"
            sbm="Сохранить"
            :handleSubmit="onSubmit"
        >
            <StringInput
                name="last_name"
                label="Фамилия"
                :value="form.last_name"
                @update:value="(val) => (form.last_name = val)"
            />
            <StringInput
                name="first_name"
                label="Имя"
                :value="form.first_name"
                @update:value="(val) => (form.first_name = val)"
            />
            <StringInput
                name="middle_name"
                label="Отчество"
                :value="form.middle_name"
                @update:value="(val) => (form.middle_name = val)"
            />
        </VerticalForm>
    </AuthenticatedLayout>
</template>

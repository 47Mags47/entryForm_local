<script>
import { router, useForm, usePage } from "@inertiajs/vue3";
import { AuthenticatedLayout } from "@layouts";
import { VerticalForm, StringInput, NumberInput, BlueButton, CheckBox } from "@components";

export default {
    components: {
        AuthenticatedLayout,
        VerticalForm,
        StringInput,
        BlueButton,
        NumberInput,
        CheckBox,
    },
    data() {
        let user = usePage().props.user.data

        return {
            form: useForm({
                first_name:     user?.first_name ?? '',
                middle_name:    user?.middle_name ?? '',
                last_name:      user?.last_name ?? '',
                phone:          user?.phone ?? '',
                office:         user?.office ?? '',
                receiveMail:    user?.receiveMail ?? false,
            }),
            user,
        };
    },
    methods: {
        onSubmit(e) {
            e.preventDefault();

            this.form.put(route("user.update", { user: this.user.id }));
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
            <NumberInput
                label="Телефон"
                name="phone"
                placeholder="+7(___) ___ __-__"
                :value="form.phone"
                @update:value="(val) => (form.phone = val)"
                autocomplete="phone"
            />
            <StringInput
                name="office"
                label="Кабинет"
                :value="form.office"
                @update:value="(val) => (form.office = val)"
            />
            <CheckBox
                name="receiveMail"
                label="Получать уведомления"
                :modelValue="form.receiveMail"
                @update:modelValue="(val) => form.receiveMail = val"
            />
        </VerticalForm>
    </AuthenticatedLayout>
</template>

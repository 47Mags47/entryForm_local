<script>
import { router, useForm, usePage } from "@inertiajs/vue3";
import { AuthenticatedLayout } from "@layouts";
import {
    HorizontalForm,
    FormGroup,
    StringInput,
    BlueButton,
    NumberInput
} from "@components";

export default {
    components: {
        AuthenticatedLayout,
        HorizontalForm,
        FormGroup,
        StringInput,
        NumberInput,
        BlueButton,
    },
    data() {
        const user = usePage().props.user.data;
        return {
            form: useForm({
                first_name: user.first_name ?? '',
                middle_name: user.middle_name ?? '',
                last_name: user.last_name ?? '',
                email: user.email ?? '',
                phone: user.phone ?? '',
                office: user.office ?? '',
            }),
            user,
        };
    },
    methods: {
        onSubmit(e) {
            e.preventDefault();
            router.get(route("user.edit", { user: this.user.id }));
        },
        goto(href, params) {
            router.get(route(href, params));
        },
    },
};
</script>

<template>
    <AuthenticatedLayout>
            <HorizontalForm
                header="Личный кабинет"
                sbm="Редактировать"
                :handleSubmit="onSubmit"
            >
                <FormGroup>
                    <StringInput
                        name="last_name"
                        label="Фамилия"
                        :value="form.last_name"
                        @update:value="(val) => (form.last_name = val)"
                        disabled
                    />
                    <StringInput
                        name="first_name"
                        label="Имя"
                        :value="form.first_name"
                        @update:value="(val) => (form.first_name = val)"
                        disabled
                    />
                    <StringInput
                        name="middle_name"
                        label="Отчество"
                        :value="form.middle_name"
                        @update:value="(val) => (form.middle_name = val)"
                        disabled
                    />
                    <StringInput
                        name="office"
                        label="Кабинет"
                        :value="form.office"
                        @update:value="(val) => (form.office = val)"
                        disabled
                    />
                </FormGroup>

                <FormGroup>
                    <StringInput
                        name="email"
                        label="Email"
                        :value="form.email"
                        @update:value="(val) => (form.email = val)"
                        disabled
                    />
                    <NumberInput
                        label="Телефон"
                        name="phone"
                        placeholder="+7(___) ___-__-__"
                        :value="form.phone"
                        @update:value="(val) => (form.phone = val)"
                        autocomplete="phone"
                        disabled
                    />
                    <br />
                    <BlueButton @click="goto('change-email')">
                        Сменить Email
                    </BlueButton>
                    <br />
                    <BlueButton @click="goto('passwordChange.edit')">
                        Сменить пароль
                    </BlueButton>
                </FormGroup>
            </HorizontalForm>
    </AuthenticatedLayout>
</template>

<style lang="sass" scoped>
.blue-button
    margin-top: 20px
</style>

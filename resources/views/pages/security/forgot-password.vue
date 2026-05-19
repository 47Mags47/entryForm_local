<script>
import { useForm } from "@inertiajs/vue3";
import { GuestLayout } from "@layouts";
import { VerticalForm, StringInput } from "@components";

export default {
    components: {
        GuestLayout,
        VerticalForm,
        StringInput,
    },

    data() {
        return {
            form: useForm({
                email: "",
            }),
        };
    },

    methods: {
        onSubmit(e) {
            e.preventDefault();
            this.form.post(route("password.email"));
        },
    },
};
</script>

<template>
    <GuestLayout>
        <VerticalForm
            header="Введите почту"
            sbm="Отправить"
            :handleSubmit="onSubmit"
        >
            <StringInput
                label="Email"
                name="email"
                :value="form.email"
                @update:value="(val) => (form.email = val)"
                autocomplete="email"
            />
        </VerticalForm>
    </GuestLayout>
</template>

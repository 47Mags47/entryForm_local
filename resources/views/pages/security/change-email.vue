<script>
import { useForm } from "@inertiajs/vue3";
import { AuthenticatedLayout } from "@layouts";
import { VerticalForm, StringInput } from "@components";

export default {
    components: {
        AuthenticatedLayout,
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

            this.form.post(route("change-email.post"));
        },
    },
};
</script>

<template>
    <AuthenticatedLayout>
        <VerticalForm
            header="Смена Email"
            sbm="Отправить"
            :handleSubmit="onSubmit"
        >
            <StringInput
                name="email"
                label="Email"
                type="email"
                :value="form.email"
                @update:value="(val) => (form.email = val)"
            />
        </VerticalForm>
    </AuthenticatedLayout>
</template>

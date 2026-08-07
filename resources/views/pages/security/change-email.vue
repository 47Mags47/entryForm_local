<script>
import { useForm } from "@inertiajs/vue3";
import { VerticalForm, StringInput } from "@components";

export default {
    components: {
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
</template>

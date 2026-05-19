<script>
import { useForm, usePage } from "@inertiajs/vue3";
import { AuthenticatedLayout } from "@layouts";
import { DivisionTab } from "@includes";
import { VerticalForm, StringInput } from "@components";

export default {
    components: {
        AuthenticatedLayout,
        DivisionTab,
        VerticalForm,
        StringInput,
    },

    data() {
        return {
            form: useForm({
                email: "",
            }),
            division: usePage().props.division.data,
        };
    },

    methods: {
        onSubmit(e) {
            e.preventDefault();
            this.form.post(route("invites.store", { division_id: this.division.id }));
        },
    },
};
</script>

<template>
    <AuthenticatedLayout>
        <DivisionTab :division_id="division.id">
            <VerticalForm
                header="Отправить приглашение"
                sbm="Отправить"
                :handleSubmit="onSubmit"
            >
                <StringInput
                    label="Email"
                    name="email"
                    :value="form.email"
                    @update:value="(val) => (form.email = val)"
                    autocomplete="username"
                />
            </VerticalForm>
        </DivisionTab>
    </AuthenticatedLayout>
</template>

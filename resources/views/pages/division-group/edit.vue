<script>
import { usePage, useForm } from "@inertiajs/vue3";
import { VerticalForm, StringInput } from "@components";

export default {
    components: {
        VerticalForm,
        StringInput,
    },

    data() {
        const division_group = usePage().props.division_group.data;

        return {
            division_group,
            form: useForm({
                name: division_group.name,
            }),
        };
    },

    methods: {
        onSubmit(e) {
            e.preventDefault();
            this.form.put(
                route("division-group.update", {
                    division_group: this.division_group.id,
                }),
            );
        },
    },
};
</script>

<template>
    <VerticalForm header="Группа" sbm="Сохранить" :handleSubmit="onSubmit">
        <StringInput
            label="Наименование"
            name="name"
            :value="form.name"
            @update:value="(val) => (form.name = val)"
            autocomplete="current-name"
        />
    </VerticalForm>
</template>

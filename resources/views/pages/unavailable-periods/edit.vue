<script>
import { usePage, router } from "@inertiajs/vue3";
import {
    VerticalForm,
    DatePicker, Select, CheckBox,
} from "@components";

export default {
    components: {
        VerticalForm,
        DatePicker, Select, CheckBox
    },

    data() {
        return {
            date_start: usePage().props.unavailable_period.data.date_start ?? null,
            date_end: usePage().props.unavailable_period.data.date_end ?? null,
        }
    },

    computed: {
        division: () => usePage().props.current_division.data,
        unavailable_period: () => usePage().props.unavailable_period.data,
    },

    methods: {
        onSubmit(e) {
            e.preventDefault();

            router.put(
                route("unavailable-periods.update", {
                    division:   this.division.id,
                    unavailable_period:     this.unavailable_period.id,
                }),
                {
                    date_start:     this.date_start,
                    date_end:       this.date_end,
                },
            );
        },
    },
};
</script>

<template>
    <VerticalForm
        header="Редактирование записи (нерабочий период)"
        sbm="сохранить"
        :handleSubmit="onSubmit"
    >
        <DatePicker
            label="Начало"
            name="date_start"
            :showAvailable="false"
            :value="date_start"
            @update:value="(val) => (date_start = val)"
        />
        <DatePicker
            label="Конец"
            name="date_end"
            :showAvailable="false"
            :value="date_end"
            @update:value="(val) => (date_end = val)"
        />
    </VerticalForm>
</template>

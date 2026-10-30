<script>
import { usePage, router } from "@inertiajs/vue3";
import {
    VerticalForm,
    DatePicker, Select, CheckBox
} from "@components";

export default {
    components: {
        VerticalForm,
        DatePicker, Select, CheckBox
    },

    data() {
        return {
            date_start: null,
            date_end: null,
        };
    },

    computed: {
        division: () => usePage().props.current_division.data,
    },

    methods: {
        onSubmit(e) {
            e.preventDefault();

            router.post(
                route("unavailable-periods.store", {
                    division:   this.division.id,
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
        header="Новая запись (нерабочий период)"
        sbm="сохранить"
        :handleSubmit="onSubmit"
    >
        <!-- HACK переделать в range-date. Не добавляю, потому что не показывается сообщение ошибки в инпутах, если есть -->
        <DatePicker
            label="Начало"
            name="date_start"
            :showAvailable="false"
            @update:value="(val) => (date_start = val)"
        />
        <DatePicker
            label="Конец"
            name="date_end"
            :showAvailable="false"
            @update:value="(val) => (date_end = val)"
        />
    </VerticalForm>
</template>

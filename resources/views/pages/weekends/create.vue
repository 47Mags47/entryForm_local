<script>
import { usePage, router } from "@inertiajs/vue3";
import { VerticalForm, DatePicker } from "@components";

export default {
    components: {
        VerticalForm,
        DatePicker,
    },

    data() {
        return {
            date_start: null,
            date_end:   null,
        }
    },

    computed: {
        current_user: () => usePage().props.current_user.data,
        division:     () => usePage().props.current_division.data,
        worker:         () => usePage().props.worker.data
    },

    methods: {
        onSubmit(e) {
            e.preventDefault();

            router.post(route('weekends.store', {
                division:   this.division.id,
                user:       this.worker.id,
            }),
            {
                date_start: this.date_start,
                date_end:   this.date_end

            })
        }
    }
};
</script>

<template>
    <VerticalForm
        header="Новая запись (отпуск)"
        sbm="сохранить"
        :handleSubmit="onSubmit"
    >
        <!-- HACK переделать в range-date. Не добавляю, потому что не показывается сообщение ошибки в инпутах, если есть -->
        <DatePicker
            label="Начальная дата"
            name="date_start"
            :showAvailable="false"
            @update:value="(val) => date_start = val"
        />
        <DatePicker
            label="Конечная дата"
            name="date_end"
            :showAvailable="false"
            @update:value="(val) => date_end = val"
        />
    </VerticalForm>
</template>

<script>
import { usePage } from "@inertiajs/vue3";
import { AuthenticatedLayout } from "@layouts";
import { DivisionTab } from "@includes";
import { Table, AddButton, EditButton, DeleteButton } from "@components";

export default {
    components: {
        AuthenticatedLayout,
        DivisionTab,
        Table,
        AddButton,
        EditButton,
        DeleteButton,
    },
    data() {
        const division = usePage().props.division.data;

        return {
            columns: [
                { key: ["status","name"], label: "Статус", width: "100px" },
                { key: "frame", label: "Фрейм", width: "700px" },
                { key: "actions", label: "" },
            ],
            division,

        };
    },
    computed:{
        frame(){
            return usePage().props.frame;
        }
    }
};

</script>

<template>
    <AuthenticatedLayout>
        <DivisionTab current="frame">
            <Table :data="frame" :columns="columns" header="Фрейм">

                <template
                    #toolbar-right
                    v-if="frame.data.length === 0"
>

                    <AddButton
                        :href="route('frame.store', { division: division.id })"
                        method="post"
                    />
                </template>
                <template #actions = {row}>
                    <EditButton
                        :href="route('frame.update', { division: division.id, frame: row.id })"
                        method="put"
                    />
                    <DeleteButton
                        :href="
                            route('frame.destroy', { division: division.id, frame: row.id })
                        "
                    />
                </template>
            </Table>
        </DivisionTab>
    </AuthenticatedLayout>
    <iframe width="1800" height="900" src="http://entry-form.local/frames/RiUGgrF1zY4OzbgXykMoi8limHXXv37J3J0K6Q2a/subscribes/create"/>
</template>

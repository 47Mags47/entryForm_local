<script>
import { usePage } from "@inertiajs/vue3";
import { AuthenticatedLayout } from "@layouts";
import { DivisionTab } from "@includes";
import { Table, AddButton, EditButton, DeleteButton, BlueButton, RefreshIco } from "@components";
import { router } from "@inertiajs/vue3";
import { h } from "vue";

export default {
    components: {
        AuthenticatedLayout,
        DivisionTab,
        Table,
        AddButton, EditButton, DeleteButton, BlueButton,
        RefreshIco
    },
    data() {
        const division = usePage().props.division.data;

        return {
            flag: false,
            columns: [
                { key: "name",    label: "город" },
                {
                    label: 'Статус',
                    width: '100px',
                    component: (row) => h('div', {
                        class: !this.flag ? 'ellipsis' : 'frame-status-error'
                    }, '...')
                },
                {
                    label: 'Код',
                    width: '70px',
                    component: (row) => h('span', {
                        class: 'ellipsis'
                    }, '...')
                },
                {
                    label: 'Время ответа',
                    width: '100px',
                    component: (row) => h('div', {
                        class: 'ellipsis'
                    }, '...')
                },
                { key: "actions", label: "" },
            ],
            division,

        };
    },
    methods: {
        updateFrame() {
            this.flag = !this.flag
            router.put(route('frame.update', { division: this.division.id, frame: this.frame.data[0].id }))
        }
    },
    computed:{
        frame(){
            return usePage().props.frame;
        },
        cities(){
            return usePage().props.cities;
        }
    }
};

</script>

<template>
    <AuthenticatedLayout>
        <DivisionTab current="frame">
            <Table :data="cities" :columns="columns" header="Фрейм">
                <template #toolbar-left>
                    <div class="frame-data">
                        <input readonly type="text" :value="frame.data[0].frame"/>
                        <BlueButton @click="updateFrame()">
                            <RefreshIco/>
                        </BlueButton>
                    </div>
                </template>
            </Table>
        </DivisionTab>
    </AuthenticatedLayout>
    <!-- <iframe width="1800" height="900" src="http://entry-form.local/frames/RiUGgrF1zY4OzbgXykMoi8limHXXv37J3J0K6Q2a/subscribes/create"/>
    <iframe src="http://entry-form.local/frames/uYYb4HRdZugG22ng2TEwThKNg9ECYjhcdoyFUuc9/subscribes/create"/> -->
</template>

<style lang="sass">
.ellipsis
    display: inline-block
    width: 0
    overflow: hidden
    animation: ellipsis 1.5s steps(4, end) infinite

.frame-status-ok
    height: 16px
    width: 16px
    background: green
    border-radius: 100%

.frame-status-error
    height: 16px
    width: 16px
    background: red
    border-radius: 100%

.frame-data
    display: flex
    align-items: center
    gap: 20px
    .blue-button
        height: 30px
        width: 50px
    input
        width: 900px

@keyframes pulse
    0%
        opacity: 1

    50%
        opacity: .5

    100%
        opacity: 1

@keyframes ellipsis
    from
        width: 0

    to
        width: 19px

</style>

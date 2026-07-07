<script>
import { getObjectValue } from "../../../js/helpers/index";
import { default as Pagination } from "../Pagination.vue";
import SpinnerIco from "../icons/SpinnerIco.vue";

export default {
    components: {
        Pagination,
        SpinnerIco
    },

    props: {
        isLoading: {
            type: Boolean,
            default: false,
        },
        data: [Array, Object],
        columns: Array,
        header: String,
        head: {
            type: Boolean,
            default: true,
        },
        toolbarVisible: {
            type: Boolean,
            default: true,
        },
    },

    methods: {
        getObjectValue(key, row) {
            return getObjectValue(key, row);
        },
    },
};
</script>

<template>
    <div class="table-container">
        <h3 v-if="header" class="table-header">{{ header }}</h3>

        <div v-if="toolbarVisible" class="table-toolbar">
            <div class="toolbar-left">
                <slot name="toolbar-left" />
            </div>
            <div class="toolbar-right">
                <slot name="toolbar-right" />
            </div>
        </div>

        <div class="table-wrapper">
            <table class="table">
                <thead v-if="head">
                    <tr>
                        <th
                            v-for="{ label, width } in columns"
                            :key="label"
                            :style="width ? { width: width } : {}"
                        >
                            {{ label }}
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <div v-if="isLoading" class="loading-ico-wrapper">
                        <SpinnerIco />
                    </div>
                    <div v-else-if="!isLoading && data?.data.length === 0" class="no-data">
                        <span>данных нет :(</span>
                    </div>
                    <tr v-else v-for="(row, r_index) in data?.data" :key="r_index">
                        <td
                            v-for="(cell, c_index) in columns"
                            :key="c_index"
                            :style="cell.width ? { width: cell.width } : {}"
                        >
                            <component
                                v-if="cell.component"
                                :is="cell.component(row)"
                            />

                            <!-- Кастомный рендер, если указан -->
                            <div v-else-if="cell.render">
                                {{ cell.render(row) }}
                            </div>

                            <!-- Стандартные случаи -->
                            <div v-else-if="cell.key === 'actions'" class="table-actions">
                                <slot name="actions" :row="row" />
                            </div>

                            <div v-else-if="cell.splitDateTime">
                                <div>
                                    {{
                                        getObjectValue(cell.key, row)?.split(" ")[0] || "-"
                                    }}
                                </div>
                                <div>
                                    {{
                                        getObjectValue(cell.key, row)?.split(" ")[1] || "-"
                                    }}
                                </div>
                            </div>

                            <div v-else>
                                {{ getObjectValue(cell.key, row) ?? "" }}
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <Pagination
                v-if="data?.meta"
                :total="data?.meta.total"
                :current="data?.meta.current_page"
                :perPage="data?.meta.per_page"
            />
        </div>
    </div>
</template>

<style lang="sass" scoped>
.table-container
    border-radius: 16px
    padding: 24px

    .table-header
        font-size: 1.5rem
        font-weight: 700
        color: var(--header-text-color)
        margin-bottom: 0.5rem

    .table-toolbar
        display: flex
        justify-content: space-between
        align-items: center
        gap: 16px
        margin-bottom: 1rem
        flex-wrap: wrap
        background: white
        z-index: 11

        .toolbar-left
            flex: 1 1 300px
            display: flex
            align-items: center

        .toolbar-right
            padding-right: 5px
            flex: 0 0 auto
            display: flex
            gap: 8px
            flex-wrap: wrap

    .table
        width: 100%
        border-collapse: separate
        border-spacing: 0
        table-layout: auto

        .loading-ico-wrapper
            position: absolute
            left: 50%
            transform: translateX(-50%)

        .no-data
            position: absolute
            left: 50%
            transform: translate(-50%, 64px)
            font-size: 24px
        td
            padding: 5px 10px
            border-right: 1px solid #ece9e7
            transition: all 0.2s ease

        th:last-child,
        td:last-child
            width: 1%
            white-space: nowrap
            text-align: center
            padding: 16px 8px !important
            min-width: 0

        thead
            tr
                th
                    background: var(--palette-color-4)
                    color: white
                    font-weight: 600
                    padding: 16px 20px
                    text-align: left
                    position: sticky
                    top: 0
                    border-right: 1px solid #ece9e7
                    z-index: 10

                    &:first-child
                        border-top-left-radius: 12px

                    &:last-child
                        border-top-right-radius: 12px
                        border-right: none

        tbody
            tr
                &:hover
                    td
                        background:#88a2ff63

                &:not(:last-child)
                    td
                        border-bottom: 1px solid #ece9e7

            td
                    &:last-child
                        border-right: none


        .table-actions
            display: flex
            gap: 10px
            align-items: center
            justify-content: start
            margin: 0 auto
</style>

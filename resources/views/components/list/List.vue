<script>
export default {
    props: {
        data: {
            type: [Array, Object],
            required: true,
        },
        columns: Array,
        head: {
            type: Boolean,
            default: true,
        },
    },

    computed: {
        rows() {
            if (Array.isArray(this.data)) return this.data;

            if (this.data && Array.isArray(this.data.data)) {
                return this.data.data;
            }

            return [];
        },
    },
};
</script>

<template>
    <div class="table-container">
        <div class="table-wrapper">
            <table class="table">
                <thead v-if="head">
                    <tr>
                        <th
                            v-for="{ label, width } in columns"
                            :key="label"
                            :style="width ? { width } : {}"
                        >
                            {{ label }}
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(row, r_index) in rows" :key="r_index">
                        <td
                            v-for="(col, c_index) in columns"
                            :key="c_index"
                            :style="col.width ? { width: col.width } : {}"
                        >
                            <slot
                                v-if="col.key === 'actions'"
                                name="actions"
                                :row="row"
                            />

                            <span v-else>
                                {{ row[col.key] }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style lang="sass" scoped>
.table-container
    border-radius: 16px
    padding: 24px

.table-wrapper
    width: 100%
    overflow-x: hidden
    overflow-y: auto
    max-height: 400px
    @include scroll()

.table
    width: 100%
    border-collapse: separate
    border-spacing: 0
    table-layout: auto

    th
        background: var(--palette-color-4)
        color: white
        font-weight: 600
        padding: 16px 20px
        text-align: left
        border-right: 1px solid #ece9e7

    th:last-child
        border-top-right-radius: 12px
        border-right: none

    td
        padding: 10px
        border-right: 1px solid #ece9e7

    td:last-child
        border-right: none

tbody tr:hover td
    background: #88a2ff63

tbody tr:not(:last-child) td
    border-bottom: 1px solid #ece9e7
</style>

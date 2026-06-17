<script>
import StringInput from "../StringInput.vue";
export default {
    components: { StringInput },
    props: {
        options: {
            type: Array,
            required: true,
        },
        modelValue: [String, Number],
        hasSearch: {
            type: Boolean,
            default: true,
        },
    },
    emits: ["select"],

    data() {
        return {
            searchQuery: "",
        };
    },

    computed: {
        filteredOptions() {
            const query = this.searchQuery.trim().toLowerCase();
            if (!query) return this.options;
            return this.options.filter((option) =>
                option.label.toLowerCase().includes(query),
            );
        },
    },

    methods: {
        onSelect(option) {
            this.$emit("select", option);
            this.searchQuery = "";
        },
    },
    mounted(){
        if(this.$refs.selected !== undefined)
            this.$refs.selected[0].scrollIntoView({ behavior: 'smooth' });
    }
};
</script>

<template>
    <div class="select-dropdown">
        <div class="search-wrapper" v-if="hasSearch">
            <input
                v-model="searchQuery"
                type="text"
                class="search-input"
                placeholder="Поиск..."
                @click.stop
            />
        </div>

        <ul>
            <li
                v-for="option in filteredOptions"
                class="select-option"
                :key="option.value"
                :class="{ 'is-selected': option.value == modelValue }"
                :ref="option.value === modelValue ? 'selected' : null"
                @click.stop="onSelect(option)"
            >
                {{ option.label }}
            </li>
            <li v-if="filteredOptions.length === 0" class="select-empty">
                {{ hasSearch ? 'Ничего не найдено' : 'Тут пусто' }}
            </li>
        </ul>
    </div>
</template>

<style lang="sass" scoped>
.select-dropdown
    position: absolute
    top: 100%
    left: 0
    right: 0
    background: var(--input-background)
    border: 1px solid var(--input-border-color)
    border-top: none
    border-radius: 0 0 7px 7px
    overflow: hidden
    z-index: 1000
    animation: slideDown 0.2s ease

.search-wrapper
    padding: 8px 8px
    border-bottom: 1px solid var(--input-border-color)
    background: var(--input-background)
    .search-input
        width: 100%

ul
    overflow-y: auto
    max-height: 120px
    @include scroll

.select-option
    font-size: 0.95rem
    padding: 10px
    cursor: pointer

    &:hover
        background: #83adf028

    &.is-selected
        background: #83adf028

.select-empty
    padding: 10px
    text-align: center
    color: #888

@keyframes slideDown
    from
        opacity: 0
        transform: translateY(-10px)
    to
        opacity: 1
        transform: translateY(0)
</style>

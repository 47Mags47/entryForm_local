<script>
import DateInput from "./DateInput.vue";
import Label from "../../Label.vue";
import { DateTime } from "luxon";
import FormItem from "../../FormItem.vue";

export default {
    components: {
        DateInput,
        Label,
        FormItem,
    },
    props: {
        modelValue: [String, Date, null],
        label: {
            type: String,
            default: null
        },
        name: {
            type: String,
            default: null
        },
        value: {
            type: [Object, String]
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        start: {
            type: [String, Object],
            default: null
        },
        end: {
            type: [String, Object],
            default: null
        },
        showAvailable: {
            type: Boolean,
            default: true,
        }
    },
    computed: {
        getValue(){
            if(typeof this.value === 'object')
                return this.value

            if(typeof this.value === 'string')
                return DateTime.fromFormat(this.value, 'yyyy-MM-dd')

            return null
        }
    },
    emits: [
        "update:value"
    ],
}
</script>

<template>
    <FormItem :name="name">
        <Label :labelText="label" />
        <DateInput
            :name
            :disabled
            :value="getValue"
            :onUpdate="(val) => $emit('update:value', val)"
            :startInterval="start"
            :endInterval="end"
        />
        <div v-if="showAvailable" class="avaible-date-container">
            <template v-if="start !== null && end === null">
                Доступны даты с {{ start.toFormat('dd.MM.yyyy') }}
            </template>
            <template v-if="start === null && end !== null">
                Доступны даты по {{ end.toFormat('dd.MM.yyyy') }}
            </template>
            <template v-if="start !== null && end !== null">
                Доступны даты с {{ start.toFormat('dd.MM.yyyy') }} по {{ end.toFormat('dd.MM.yyyy') }}
            </template>
        </div>
    </FormItem>
</template>

<style lang="sass">
.avaible-date-container
    font-size: .8rem
    color: var(--label-color)
</style>

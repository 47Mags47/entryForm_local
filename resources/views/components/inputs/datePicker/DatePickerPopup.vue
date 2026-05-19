<script setup>
import { ref, computed, watch, onMounted } from "vue";
import dayjs from "dayjs";
import "dayjs/locale/ru";
dayjs.locale("ru");

const props = defineProps({
    modelValue: String,
});
const emit = defineEmits(["update:internal"]);

const currentMonth = ref(dayjs().month());
const currentYear = ref(dayjs().year());
const selectedDate = ref(dayjs());

function initializeFromModelValue() {
    if (props.modelValue) {
        const d = dayjs(props.modelValue, "YYYY-MM-DD", true);
        if (d.isValid()) {
            selectedDate.value = d;
            currentMonth.value = d.month();
            currentYear.value = d.year();
        }
    }
}

watch(
    () => props.modelValue,
    () => {
        initializeFromModelValue();
    },
    { immediate: true },
);

const monthLabel = computed(() =>
    dayjs(`${currentYear.value}-${currentMonth.value + 1}-01`).format(
        "MMMM YYYY",
    ),
);

const days = computed(() => {
    const start = dayjs(`${currentYear.value}-${currentMonth.value + 1}-01`);
    const daysInMonth = start.daysInMonth();
    let firstDay = start.day();
    firstDay = firstDay === 0 ? 6 : firstDay - 1;
    const arr = [];
    for (let i = 0; i < firstDay; i++) arr.push(null);
    for (let i = 1; i <= daysInMonth; i++) {
        arr.push(dayjs(`${currentYear.value}-${currentMonth.value + 1}-${i}`));
    }
    return arr;
});

function selectDate(day, e) {
    if (!day) return;
    e?.stopPropagation();
    selectedDate.value = day;
    emit("update:internal", day.format("YYYY-MM-DD"));
}

function prevMonth() {
    const prev = dayjs(
        `${currentYear.value}-${currentMonth.value + 1}-01`,
    ).subtract(1, "month");
    currentMonth.value = prev.month();
    currentYear.value = prev.year();
}

function nextMonth() {
    const next = dayjs(`${currentYear.value}-${currentMonth.value + 1}-01`).add(
        1,
        "month",
    );
    currentMonth.value = next.month();
    currentYear.value = next.year();
}

onMounted(() => {
    initializeFromModelValue();
});
</script>

<template>
    <div class="calendar-popup datepicker-popup-teleport" @click.stop>
        <div class="calendar-header">
            <button class="nav-btn" @click="prevMonth">‹</button>
            <div class="month-label">{{ monthLabel }}</div>
            <button class="nav-btn" @click="nextMonth">›</button>
        </div>
        <div class="calendar-grid">
            <div
                v-for="d in ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс']"
                :key="d"
                class="weekday"
            >
                {{ d }}
            </div>
            <div
                v-for="(day, i) in days"
                :key="i"
                class="day-cell"
                :class="{
                    empty: !day,
                    selected: day && day.isSame(selectedDate, 'day'),
                    today: day && day.isSame(dayjs(), 'day'),
                }"
                @click="selectDate(day, $event)"
            >
                <span v-if="day">{{ day.date() }}</span>
            </div>
        </div>
    </div>
</template>

<style scoped lang="sass">
.calendar-popup
  background: #fff
  border: 1px solid var(--blue-button-background-color-hover)
  border-radius: 12px
  padding: 8px
  width: 260px
  box-shadow: 0 4px 20px rgba(0,0,0,0.15)
  font-family: Inter, sans-serif
  user-select: none
.calendar-header
  display: flex
  justify-content: space-between
  align-items: center
  padding: 6px 0
  font-weight: 500
  font-size: 14px
.nav-btn
  background: none
  border: none
  cursor: pointer
  font-size: 18px
  color: #374151
  padding: 0 8px
  border-radius: 6px
  &:hover
    background: #f3f4f6
.month-label
  text-transform: capitalize
.calendar-grid
  display: grid
  grid-template-columns: repeat(7, 1fr)
  gap: 4px
  text-align: center
  margin-top: 6px
.weekday
  font-size: 13px
  color: #9ca3af
.day-cell
  padding: 6px
  border-radius: 6px
  cursor: pointer
  font-size: 14px
  transition: background 0.15s
  &.empty
    cursor: default
  &:hover:not(.empty)
    background: #f3f4f6
  &.selected
    background: var(--blue-button-background-color)
    color: #fff
  &.today
    border: 1px solid var(--blue-button-background-color-hover)
</style>

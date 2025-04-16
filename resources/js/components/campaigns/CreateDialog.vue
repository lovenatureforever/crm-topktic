<script setup>
import axios from '@axios';
import { toRef } from 'vue';

const props = defineProps({
    isDialogVisible: {
        type: Boolean,
        required: false
    },
    isUpdate: {
      type: Boolean,
      required: true
    },
    campaign: {
      type: Object,
      required: true
    },
    categories: {
      type: Array,
      required: true
    },
    sub_categories: {
      type: Array,
      required: true
    }
})

const emit = defineEmits([
    'close-dialog'
])

const isDialogVisible = toRef(props, 'isDialogVisible')
const campaign = toRef(props, 'campaign')

const categories = toRef(props, 'categories')

const sub_categories = toRef(props, 'sub_categories')

const filtered_subcategories = computed(()=> {
  const category = campaign.value.category;
  return sub_categories.value.filter(item => item.parent == category)
})

const isError = computed(() => error.value.length > 0)
const error = ref('')

const close = () => {
    emit('close-dialog', false)
}

const save = () => {
  const _campaign = campaign.value
  if (_campaign.name.length == 0) {
    error.value = 'Campaign Name must be not empty.'
    return;
  }

  if (_campaign.company.length == 0) {
    error.value = 'Company must be not empty.'
    return;
  }

  if (_campaign.category.length == 0) {
    error.value = 'Category must be not empty.'
    return;
  }

  if (_campaign.sub_category.length == 0) {
    error.value = 'Sub Category must be not empty.'
    return;
  }

  if (_campaign.start_date.length == 0) {
    error.value = 'You must select start date.'
    return;
  }

  if (_campaign.end_date.length == 0) {
    error.value = 'You must select end date.'
    return;
  }

  if (_campaign.status != 'active' && _campaign.status != 'inactive') {
    error.value = 'Status is wrong.'
    return;
  }

  _campaign.start_date = new Date(_campaign.start_date).toISOString().slice(0, 19).replace('T', ' ')
  _campaign.end_date = new Date(_campaign.end_date).toISOString().slice(0, 19).replace('T', ' ')

  _campaign.category = categories.value.find(item => item.value == _campaign.category).title
  _campaign.sub_category = sub_categories.value.find(item => item.value == _campaign.sub_category).title

  if (props.isUpdate)
    axios.put('/admin/campaigns', _campaign)
      .then((res) => {
        emit('close-dialog', true)
      })
  else
    axios.post('/admin/campaigns', _campaign)
      .then((res) => {
        emit('close-dialog', true)
      })

}

onMounted(() => {
  if (categories.length > 0) {
    campaign.value.category = categories[0].value
  }
})

</script>

<template>
  
  <VDialog
    v-model="isDialogVisible"
    persistent
    max-width="720"
  >
    <!-- Dialog close btn -->
    <DialogCloseBtn @click="close" />

    <!-- Dialog Content -->
    <VCard title="Campaign Detail">
      <VCardText class="px-8">
        <VRow class="mt-4">
          <VCol
            cols="12"
            md="6"
          >
            <AppTextField
              v-model="campaign.name"
              label="Campaign Name"
            />
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <AppTextField
              v-model="campaign.company"
              label="Company"
            />
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <AppSelect
              v-model="campaign.category"
              :items="categories"
              label="Category"
            />
          </VCol>
          <VCol
            cols="12"
            md="6"
          >

            <AppSelect
              v-model="campaign.sub_category"
              :items="filtered_subcategories"
              label="Sub Category"
            />
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <VLabel
              for="start-date"
              class="mb-1 text-body-2 text-high-emphasis"
            >
              Start Date
            </VLabel>

            <AppDateTimePicker
              id="end-date"
              class="mr-4"
              v-model="campaign.start_date"
            />
          </VCol>
          <VCol
            cols="12"
            md="6"
          >
            <VLabel
              for="end-date"
              class="mb-1 text-body-2 text-high-emphasis"
            >
              End Date
            </VLabel>

            <AppDateTimePicker
              class="mr-4"
              v-model="campaign.end_date"
            />
          </VCol>
          <VCol 
            cols="12"
            md="6">
            <AppTextField
              v-model="campaign.today_sales"
              label="Today Sales"
              type="number"
            />
          </VCol>
          <VCol 
            cols="12"
            md="6">
            <AppTextField
              v-model="campaign.total_sales"
              label="Total Sales"
              type="number"
            />
          </VCol>
          
        </VRow>
      </VCardText>

      <VCardText class="d-flex justify-end flex-wrap gap-3">
        <VBtn
          variant="tonal"
          color="secondary"
          @click="close"
        >
          Close
        </VBtn>
        <VBtn @click="save">
          Save
        </VBtn>
      </VCardText>
    </VCard>
    <VSnackbar
      v-model="isError"
      :timeout="1000"
      location="top end"
      color="error"
    >
      {{ error }}
    </VSnackbar>
  </VDialog>
</template>

<style lang="scss">
/* stylelint-disable no-descending-specificity */
@use "flatpickr/dist/flatpickr.css";
@use "@core-scss/base/mixins";

.flat-picker-custom-style {
  position: absolute;
  color: inherit;
  inline-size: 100%;
  inset: 0;
  outline: none;
  padding-block: 0;
  padding-inline: var(--v-field-padding-start);
}

$heading-color: rgba(var(--v-theme-on-background), var(--v-high-emphasis-opacity));
$body-color: rgba(var(--v-theme-on-background), var(--v-medium-emphasis-opacity));
$disabled-color: rgba(var(--v-theme-on-background), var(--v-disabled-opacity));

// hide the input when your picker is inline
input[altinputclass="inlinePicker"] {
  display: none;
}

.flatpickr-calendar {
  background-color: rgb(var(--v-theme-surface));
  inline-size: 16.625rem;
  margin-block-start: 0.1875rem;

  @include mixins.elevation(4);

  .flatpickr-rContainer {
    .flatpickr-weekdays {
      block-size: 2.125rem;
      padding-inline: 0.875rem;
    }

    .flatpickr-days {
      min-inline-size: 16.625rem;

      .dayContainer {
        justify-content: center !important;
        inline-size: 16.625rem;
        min-inline-size: 16.625rem;
        padding-block-end: 0.75rem;
        padding-block-start: 0;

        .flatpickr-day {
          block-size: 2.125rem;
          font-size: 0.9375rem;
          line-height: 2.125rem;
          margin-block-start: 0 !important;
          max-inline-size: 2.125rem;
        }
      }
    }
  }

  .flatpickr-day {
    color: $body-color;

    &.today {
      border-color: rgb(var(--v-theme-primary));

      &:hover {
        border-color: rgb(var(--v-theme-primary));
        background: transparent;
        color: $body-color;
      }
    }

    &.selected,
    &.selected:hover {
      border-color: rgb(var(--v-theme-primary));
      background: rgb(var(--v-theme-primary));
      color: rgb(var(--v-theme-on-primary));

      @include mixins.elevation(2);
    }

    &.inRange,
    &.inRange:hover {
      border: none;
      background: rgba(var(--v-theme-primary), var(--v-activated-opacity)) !important;
      box-shadow: none !important;
      color: rgb(var(--v-theme-primary));
    }

    &.startRange {
      @include mixins.elevation(2);
    }

    &.endRange {
      @include mixins.elevation(2);
    }

    &.startRange,
    &.endRange,
    &.startRange:hover,
    &.endRange:hover {
      border-color: rgb(var(--v-theme-primary));
      background: rgb(var(--v-theme-primary));
      color: rgb(var(--v-theme-on-primary));
    }

    &.selected.startRange + .endRange:not(:nth-child(7n + 1)),
    &.startRange.startRange + .endRange:not(:nth-child(7n + 1)),
    &.endRange.startRange + .endRange:not(:nth-child(7n + 1)) {
      box-shadow: -10px 0 0 rgb(var(--v-theme-primary));
    }

    &.flatpickr-disabled,
    &.prevMonthDay:not(.startRange,.inRange),
    &.nextMonthDay:not(.endRange,.inRange) {
      opacity: var(--v-disabled-opacity);
    }

    &:hover {
      border-color: transparent;
      background: rgba(var(--v-theme-on-surface), 0.08);
    }
  }

  .flatpickr-weekday {
    color: $heading-color;
    font-size: 0.8125rem;
    font-weight: 500;
  }

  .flatpickr-days {
    inline-size: 16.625rem;
  }

  &::after,
  &::before {
    display: none;
  }

  .flatpickr-months {
    border-block-end: 1px solid rgba(var(--v-border-color), var(--v-border-opacity));

    .flatpickr-prev-month,
    .flatpickr-next-month {
      fill: $body-color;

      &:hover i,
      &:hover svg {
        fill: $body-color;
      }
    }
  }

  .flatpickr-current-month span.cur-month {
    font-weight: 300;
  }

  &.open {
    // Open calendar above overlay
    z-index: 2401;
  }

  &.hasTime.open {
    .flatpickr-time {
      border-color: rgba(var(--v-border-color), var(--v-border-opacity));
      block-size: auto;
    }

    .flatpickr-hour,
    .flatpickr-minute,
    .flatpickr-am-pm {
      font-size: 0.9375rem;
    }
  }
}

.v-theme--dark .flatpickr-calendar {
  box-shadow: 0 3px 14px 0 rgb(15 20 34 / 38%);
}

// Time picker hover & focus bg color
.flatpickr-time input:hover,
.flatpickr-time .flatpickr-am-pm:hover,
.flatpickr-time input:focus,
.flatpickr-time .flatpickr-am-pm:focus {
  background: transparent;
}

// Time picker
.flatpickr-time {
  .flatpickr-am-pm,
  .flatpickr-time-separator,
  input {
    color: $body-color;
  }

  .numInputWrapper {
    span {
      &.arrowUp {
        &::after {
          border-block-end-color: rgb(var(--v-border-color));
        }
      }

      &.arrowDown {
        &::after {
          border-block-start-color: rgb(var(--v-border-color));
        }
      }
    }
  }
}

//  Added bg color for flatpickr input only as it has default readonly attribute
.flatpickr-input[readonly],
.flatpickr-input ~ .form-control[readonly],
.flatpickr-human-friendly[readonly] {
  background-color: inherit;
  opacity: 1 !important;
}

// week sections
.flatpickr-weekdays {
  margin-block: 12px;
}

// Month and year section
.flatpickr-current-month {
  .flatpickr-monthDropdown-months {
    appearance: none;
  }

  .flatpickr-monthDropdown-months,
  .numInputWrapper {
    padding: 2px;
    border-radius: 4px;
    color: $heading-color;
    font-size: 0.9375rem;
    font-weight: 500;
    transition: all 0.15s ease-out;

    span {
      display: none;
    }

    .flatpickr-monthDropdown-month {
      background-color: rgb(var(--v-theme-surface));
    }

    .numInput.cur-year {
      font-weight: 500;
    }
  }
}

.flatpickr-day.flatpickr-disabled,
.flatpickr-day.flatpickr-disabled:hover {
  color: $body-color;
}

.flatpickr-months {
  padding-block: 0.75rem;
  padding-inline: 1rem;

  .flatpickr-prev-month,
  .flatpickr-next-month {
    display: flex;
    align-items: center;
    border-radius: 5rem;
    background: rgba(var(--v-theme-surface-variant), var(--v-selected-opacity));
    block-size: 1.75rem;
    inline-size: 1.75rem;
    inset-block-start: 0.75rem !important;
    margin-block: 0.1875rem;
    padding-block: 0.25rem;
    padding-inline: 0.4375rem;
  }

  .flatpickr-next-month {
    inset-inline-end: 1.05rem !important;
  }

  .flatpickr-prev-month {
    /* stylelint-disable-next-line liberty/use-logical-spec */
    right: 3.8rem;
    left: unset !important;
  }

  .flatpickr-month {
    display: flex;
    align-items: center;
    block-size: 2.125rem;

    .flatpickr-current-month {
      display: flex;
      align-items: center;
      padding: 0;
      block-size: 1.75rem;
      inset-inline-start: 0;
      text-align: start;
    }
  }
}

// Update hour font-weight
.flatpickr-time input.flatpickr-hour {
  font-weight: 400;
}
</style>

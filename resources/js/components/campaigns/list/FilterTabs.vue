<!-- eslint-disable vue/custom-event-name-casing -->
<!-- eslint-disable vue/prop-name-casing -->
<script setup>
const props = defineProps({
  filterList: {
    type: Object,
    required: false,
  },
  status: {
    type: String,
    required: true,
  },
  sub_status: {
    type: String,
    required: true,
  },
  is_custom: {
    type: String,
    required: false,
  },
})

const emit = defineEmits(["set-filter", "set-custom-filter"])
const currentFilter = toRef(props, "status")
const currentSubFilter = toRef(props, "sub_status")
const isCustom = toRef(props, "is_custom")
const submissionLbl = "submission"

const setFilter = (label, sitem) => {
  emit("set-filter", {
    status: label,
    sub_status: sitem,
  })
}

const setCustomFilter = () => {
  emit("set-custom-filter")
}

const setParentFilter = label => {
  if (label == "Default") setFilter("Default", "")
  else if (label == submissionLbl) setFilter(submissionLbl, submissionLbl)
}
</script>

<!-- eslint-disable vue/no-restricted-class -->
<template>
  <div class="filter-tabs">
    <div class="d-flex w-full">
      <small>Filter: </small>
    </div>
    <div class="mb-2">
      <VMenu
        v-for="(filter, index) in props.filterList"
        :key="index"
      >
        <template #activator="{ props }">
          <VBtn
            v-if="filter.items.All > 0"
            variant="text"
            :color="
              !isCustom &&
                filter.key.toLowerCase() == currentFilter.toLowerCase()
                ? 'primary'
                : 'black'
            "
            v-bind="props"
            @click="setParentFilter(filter.key)"
          >
            <VBadge
              class="custom__badge mr-4"
              :content="filter.items.All"
              :offset-x="-8"
              :offset-y="-8"
            >
              {{ filter.key }}
            </VBadge>

            <VIcon
              v-if="Object.keys(filter.items).length > 1"
              icon="tabler-chevron-down"
            />
          </VBtn>
        </template>

        <VList v-if="Object.keys(filter.items).length > 1">
          <VListItem
            v-for="(scount, sitem) in filter.items"
            :key="sitem"
            :active="sitem.toLowerCase() == currentSubFilter.toLowerCase() && filter.key.toLowerCase() == currentFilter.toLowerCase()"
            @click="setFilter(filter.key, sitem)"
          >
            <VBadge
              class="custom__badge"
              :content="scount"
              :offset-x="-18"
              :offset-y="6"
            >
              {{ sitem }}
            </VBadge>
          </VListItem>
        </VList>
      </VMenu>
      <VBtn
        class="ml-2"
        variant="outlined"
        :color="isCustom ? 'primary' : 'black'"
        @click="setCustomFilter"
      >
        Custom Filter
      </VBtn>
    </div>
  </div>
</template>

<style lang="scss">
.filter-tabs {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  padding: 10px;
  border-radius: 10px;
  background-color: rgba(var(--v-theme-warning), 0.4) !important;
  margin-block-start: -10px;
}

.custom__badge .v-badge__badge {
  border-radius: 10px !important;
  block-size: 1.2rem !important;
  font-size: 0.7rem !important;
}

.v-list-item__content {
  padding-inline-end: 30px;
}

.w-full {
  inline-size: 100%;
}
</style>

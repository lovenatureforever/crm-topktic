<template>
    <div>        
        <VNavigationDrawer
            v-model="isNavDrawerOpen"
            temporary
            border="0"
            location="end"
            width="400"
            :scrim="false"
            class="app-customizer"
        >
            <div class="customizer-heading d-flex align-center justify-space-between">
                <div>
                    <h6 class="text-h6">
                        Custom Filter
                    </h6>
                    <span class="text-body-1">Customize fields for Filter</span>
                </div>
                <IconBtn @click="closeCustomFilter">
                    <VIcon
                        icon="tabler-x"
                        size="20"
                    />
                </IconBtn>
            </div>

            <VDivider />

            <PerfectScrollbar
                tag="ul"
                :options="{ wheelPropagation: false }"
            >
                <div class="mx-6 pt-4">
                    <div class="mt-2 d-flex align-end justify-start">
                        <AppDateTimePicker
                            v-model="dateFilter"
                            label="Period"
                            :config="{ mode: 'range' }"
                            />
                        <IconBtn 
                            class="mr-4" 
                            color="primary" 
                            :disabled="!dateFilter || dateFilter == ''"
                            @click="clearDateFilter">
                            <VIcon icon="tabler-eraser" />
                        </IconBtn>
                    </div>
                    <AppTextField
                        class="mt-2"
                        v-model="customFilter.applicantname"
                        label="Applicant Name"
                    />                            
                    
                    <AppTextField
                        class="mt-2"
                        v-model="customFilter.applicantidentity"
                        label="Applicant Identity"
                    />

                    <VBtn
                        block
                        class="mt-4"
                        @click="applyCustomFilter">
                        Apply Custom Filter
                    </VBtn>

                </div>
            </PerfectScrollbar>
        </VNavigationDrawer>
        <VSnackbar
            v-model="isError"
            :timeout="1000"
            location="top end"
            color="error"
        >
            {{ error }}
        </VSnackbar>
    </div>
</template>
<script setup>
import { toRef } from '@vueuse/core';

const props = defineProps({
    isNavDrawerOpen: {
        type: Boolean,
        required: true,
    }
})

const isError = computed(() => error.value.length > 0)
const error = ref('')
const dateFilter = ref("")

const clearDateFilter = () => {
  dateFilter.value = ""
}

const customFilter = ref({
    applicantname: '',
    applicantidentity: ''
})

const emit = defineEmits([
    "close-custom-filter"
])

const isNavDrawerOpen = toRef(props, 'isNavDrawerOpen')

const applyCustomFilter = () => {
    const param = customFilter.value
    const date_filter = dateFilter.value
    let start_date = null, end_date = null
    if (date_filter && date_filter.length > 0) {
      [start_date, end_date] = date_filter.split(" to ")
      if (!end_date)
        end_date = start_date
    }    

    emit("apply-custom-filter", {
        applicantname: param.applicantname.trim(),
        applicantidentity: param.applicantidentity.trim(),
        start_date, 
        end_date
    })
}

const closeCustomFilter = () => {
    emit("close-custom-filter")
}

</script>
<style lang="scss">
.app-customizer {
  .customizer-section {
    padding: 1.25rem;
  }

  .customizer-heading {
    padding-block: 0.875rem;
    padding-inline: 1.25rem;
  }

  .v-navigation-drawer__content {
    display: flex;
    flex-direction: column;
  }

  padding-top: 80px;
}
</style>

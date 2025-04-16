<script setup>
import { defaultCampaignDetailFilter, no_filter } from "@/plugins/constant"
import axios from "@axios"

const emit = defineEmits(['setCustomFilter'])

const categories = ref([])
const sub_categories = ref([])
const racenames = ref([])

const filterValue = ref(defaultCampaignDetailFilter)

const convertCategory = _categories =>
  _categories.map(item => ({
    value: item.id,
    title: item.name,
    parent: item.parent_id,
  }))

const getCategories = () => {
  const defaultCategory = {
    id: -2,
    name: "Default",
    parent_id: null,
  }

  axios.get("/admin/progress-categories").then(res => {
    const { data } = res.data

    categories.value = convertCategory([defaultCategory, ...data.categories])
    sub_categories.value = convertCategory(data.sub_categories)
  })

  axios.get("/admin/campaigns/racenames").then(res => {
    racenames.value = ["All", ...res.data.racenames]
    filterValue.value.racename = "All"
  })
}

const filtered_subcategories = computed(() => {
  if (filterValue.value.category == undefined) return []

  return sub_categories.value.filter(
    item => item.parent == filterValue.value.category,
  )
})

const filter_categories = computed(() => {
  return [no_filter, ...categories.value]
})

const filter_sub_categories = computed(() => {
  filterValue.value.sub_category = no_filter.value

  return [no_filter, ...filtered_subcategories.value]
})

const applyFilter = () => {
  emit("setCustomFilter", filterValue.value)
}

onMounted(() => {
  getCategories()
})
</script>

<template>
  <div>
    <div class="d-flex align-center justify-between mb-4">
      <span class="mr-2">Filter</span>
      <VBtn @click="applyFilter">
        Apply
      </VBtn>
    </div>
    <VExpansionPanels multiple>
      <VExpansionPanel>
        <VExpansionPanelTitle>Filter Basic: </VExpansionPanelTitle>
        <VExpansionPanelText>
          <AppSelect
            v-model="filterValue.category"
            class="mt-2"
            :items="filter_categories"
            variant="outlined"
            label="Progress Status"
          />

          <AppSelect
            v-model="filterValue.sub_category"
            class="mt-2"
            :items="filter_sub_categories"
            variant="outlined"
            label="Sub Status"
          />

          <AppDateTimePicker
            v-model="filterValue.importDate"
            class="mt-2"
            label="Import Date"
          />

          <VTextField
            v-model="filterValue.productgroup"
            class="mt-2"
            label="Product Group"
          />

          <VTextField
            v-model="filterValue.productname"
            class="mt-2"
            label="Product Name"
          />

          <VTextField
            v-model="filterValue.applicantidentity"
            class="mt-2"
            label="Applicant Identity"
          />
        </VExpansionPanelText>
      </VExpansionPanel>

      <VExpansionPanel>
        <VExpansionPanelTitle>Filter Applicant: </VExpansionPanelTitle>
        <VExpansionPanelText>
          <VTextField
            v-model="filterValue.applicanttypename"
            class="mt-2"
            label="Applicant Type Name"
          />

          <VTextField
            v-model="filterValue.applicantcompany"
            class="mt-2"
            label="Applicant Company"
          />

          <VTextField
            v-model="filterValue.applicantbusinessregistrationnumber"
            class="mt-2"
            label="Application Business Registration Number"
          />

          <AppSelect
            v-model="filterValue.racename"
            class="mt-2"
            :items="racenames"
            variant="outlined"
            label="Racename"
          />

          <VTextField
            v-model="filterValue.applicantname"
            class="mt-2"
            label="Applicant Name"
          />

          <VTextField
            v-model="filterValue.applicantmobile"
            class="mt-2"
            label="Applicant Mobile"
          />

          <VTextField
            v-model="filterValue.applicantfax"
            class="mt-2"
            label="Applicant Fax"
          />

          <VTextField
            v-model="filterValue.applicantaddress1"
            class="mt-2"
            label="Applicant Address1"
          />

          <VTextField
            v-model="filterValue.applicantaddress2"
            class="mt-2"
            label="Applicant Address2"
          />

          <VTextField
            v-model="filterValue.applicantaddress3"
            class="mt-2"
            label="Applicant Address3"
          />

          <VTextField
            v-model="filterValue.applicantpostcode"
            class="mt-2"
            label="Applicant Postcode"
          />

          <VTextField
            v-model="filterValue.applicantcity"
            class="mt-2"
            label="Applicant City"
          />

          <VTextField
            v-model="filterValue.applicantstate"
            class="mt-2"
            label="Applicant State"
          />

          <VTextField
            v-model="filterValue.applicantemail"
            class="mt-2"
            label="Applicant Email"
          />
        </VExpansionPanelText>
      </VExpansionPanel>

      <VExpansionPanel>
        <VExpansionPanelTitle>Filter Instanllation: </VExpansionPanelTitle>
        <VExpansionPanelText>
          <VTextField
            v-model="filterValue.installationaddress1"
            class="mt-2"
            label="Installation Address1"
          />

          <VTextField
            v-model="filterValue.installationaddress2"
            class="mt-2"
            label="Installation Address2"
          />

          <VTextField
            v-model="filterValue.installationaddress3"
            class="mt-2"
            label="Installation Address3"
          />

          <VTextField
            v-model="filterValue.installationpostcode"
            class="mt-2"
            label="Installation Postcode"
          />

          <VTextField
            v-model="filterValue.installationcity"
            class="mt-2"
            label="Installation City"
          />

          <VTextField
            v-model="filterValue.installationstate"
            class="mt-2"
            label="Installation State"
          />

          <VTextField
            v-model="filterValue.installationpropertytype"
            class="mt-2"
            label="Installation Property Type"
          />

          <VTextField
            v-model="filterValue.installationcontactperson"
            class="mt-2"
            label="Installation Contact Person"
          />

          <VTextField
            v-model="filterValue.installationcontactnumber"
            class="mt-2"
            label="Installation Contact Number"
          />
        </VExpansionPanelText>
      </VExpansionPanel>

      <VExpansionPanel>
        <VExpansionPanelTitle>Filter Billing: </VExpansionPanelTitle>
        <VExpansionPanelText>
          <VTextField
            v-model="filterValue.billingaddress1"
            class="mt-2"
            label="Billing Address1"
          />

          <VTextField
            v-model="filterValue.billingaddress2"
            class="mt-2"
            label="Billing Address2"
          />

          <VTextField
            v-model="filterValue.billingaddress3"
            class="mt-2"
            label="Billing Address3"
          />

          <VTextField
            v-model="filterValue.billingpostcode"
            class="mt-2"
            label="Billing Postcode"
          />

          <VTextField
            v-model="filterValue.billingcity"
            class="mt-2"
            label="Billing City"
          />

          <VTextField
            v-model="filterValue.billingstate"
            class="mt-2"
            label="Billing State"
          />
        </VExpansionPanelText>
      </VExpansionPanel>
    </VExpansionPanels>
  </div>
</template>

<script setup>
import { defaultCampaignDetailFilter, no_filter } from '@/plugins/constant';
import axios from '@axios';

const categories = ref([])
const sub_categories = ref([])
const racenames = ref([])

const filterValue = ref(defaultCampaignDetailFilter)

const emit = defineEmits([
    'set-custom-filter'
])

const convertCategory = (_categories) => _categories.map(item => ({
    value: item.id, 
    title: item.name,
    parent: item.parent_id
}))

const getCategories = () => {
    const defaultCategory = {
        id: -2,
        name: "Default",
        parent_id: null
    }
    
    axios.get('/admin/progress-categories')
        .then(res => {
            const { data } = res.data        
            categories.value = convertCategory([defaultCategory, ...data.categories])
            sub_categories.value = convertCategory(data.sub_categories)
        })

    axios.get('/admin/campaigns/racenames')
        .then(res => {
          racenames.value = ['All', ...res.data.racenames]   
          filterValue.value.racename = 'All'
        })
}

const filtered_subcategories = computed(()=> {
    if (filterValue.value.category == undefined)
        return []

    return sub_categories.value.filter(item => item.parent == filterValue.value.category)
})

const filter_categories = computed(() => {
    return [no_filter, ...categories.value]
})

const filter_sub_categories = computed(() => {
    filterValue.value.sub_category = no_filter.value
    return [no_filter, ...filtered_subcategories.value]
})

const applyFilter = () => {
    emit('set-custom-filter', filterValue.value)
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
                        class="mt-2"
                        :items="filter_categories"
                        v-model="filterValue.category"
                        variant="outlined"
                        label="Progress Status"
                    />

                    <AppSelect
                        class="mt-2"
                        :items="filter_sub_categories"
                        v-model="filterValue.sub_category"
                        variant="outlined"
                        label="Sub Status"
                    />

                    <AppDateTimePicker
                        class="mt-2"
                        label="Import Date"
                        v-model="filterValue.importDate"
                    />                  
                    
                    <VTextField
                        class="mt-2"
                        v-model="filterValue.productgroup"
                        label="Product Group"
                    />

                    <VTextField
                        class="mt-2"
                        v-model="filterValue.productname"
                        label="Product Name"
                    />
                </VExpansionPanelText>
            </VExpansionPanel>

            <VExpansionPanel>
                <VExpansionPanelTitle>Filter Applicant: </VExpansionPanelTitle>
                <VExpansionPanelText>

                    <VTextField
                        class="mt-2"
                        v-model="filterValue.applicanttypename"
                        label="Applicant Type Name"
                    />

                    <VTextField
                        class="mt-2"
                        v-model="filterValue.applicantcompany"
                        label="Applicant Company"
                    />

                    <VTextField
                        class="mt-2"
                        v-model="filterValue.applicantbusinessregistrationnumber"
                        label="Application Business Registration Number"
                    />

                    <AppSelect
                        class="mt-2"
                        :items="racenames"
                        v-model="filterValue.racename"
                        variant="outlined"
                        label="Racename"
                    />

                    <VTextField
                        class="mt-2"
                        v-model="filterValue.applicantname"
                        label="Applicant Name"
                    />

                    <VTextField
                        class="mt-2"
                        v-model="filterValue.applicantidentity"
                        label="Applicant Identity"
                    />

                    <VTextField
                        class="mt-2"
                        v-model="filterValue.applicantmobile"
                        label="Applicant Mobile"
                    />

                    <VTextField
                        class="mt-2"
                        v-model="filterValue.applicantfax"
                        label="Applicant Fax"
                    />

                    <VTextField
                        class="mt-2"
                        v-model="filterValue.applicantaddress1"
                        label="Applicant Address1"
                    />

                    <VTextField
                        class="mt-2"
                        v-model="filterValue.applicantaddress2"
                        label="Applicant Address2"
                    />

                    <VTextField
                        class="mt-2"
                        v-model="filterValue.applicantaddress3"
                        label="Applicant Address3"
                    />

                    <VTextField
                        class="mt-2"
                        v-model="filterValue.applicantpostcode"
                        label="Applicant Postcode"
                    />

                    <VTextField
                        class="mt-2"
                        v-model="filterValue.applicantcity"
                        label="Applicant City"
                    />

                    <VTextField
                        class="mt-2"
                        v-model="filterValue.applicantstate"
                        label="Applicant State"
                    />

                    <VTextField
                        class="mt-2"
                        v-model="filterValue.applicantemail"
                        label="Applicant Email"
                    />
                </VExpansionPanelText>
            </VExpansionPanel>

            <VExpansionPanel>
                <VExpansionPanelTitle>Filter Instanllation: </VExpansionPanelTitle>
                <VExpansionPanelText>
                    <VTextField
                        class="mt-2"                        
                        v-model="filterValue.installationaddress1"
                        label="Installation Address1"
                    />

                    <VTextField
                        class="mt-2"
                        v-model="filterValue.installationaddress2"
                        label="Installation Address2"
                    />

                    <VTextField
                        class="mt-2"
                        v-model="filterValue.installationaddress3"
                        label="Installation Address3"
                    />

                    <VTextField
                        class="mt-2"
                        v-model="filterValue.installationpostcode"
                        label="Installation Postcode"
                    />

                    <VTextField
                        class="mt-2"
                        v-model="filterValue.installationcity"
                        label="Installation City"
                    />

                    <VTextField
                        class="mt-2"
                        v-model="filterValue.installationstate"
                        label="Installation State"
                    />

                    <VTextField
                        class="mt-2"
                        v-model="filterValue.installationpropertytype"
                        label="Installation Property Type"
                    />

                    <VTextField
                        class="mt-2"
                        v-model="filterValue.installationcontactperson"
                        label="Installation Contact Person"
                    />

                    <VTextField
                        class="mt-2"
                        v-model="filterValue.installationcontactnumber"
                        label="Installation Contact Number"
                    />
                </VExpansionPanelText>
            </VExpansionPanel>

            <VExpansionPanel>
                <VExpansionPanelTitle>Filter Billing: </VExpansionPanelTitle>
                <VExpansionPanelText>
                    <VTextField
                        class="mt-2"
                        v-model="filterValue.billingaddress1"
                        label="Billing Address1"
                    />
                    
                    <VTextField
                        class="mt-2"
                        v-model="filterValue.billingaddress2"
                        label="Billing Address2"
                    />
                    
                    <VTextField
                        class="mt-2"
                        v-model="filterValue.billingaddress3"
                        label="Billing Address3"
                    />
                    
                    <VTextField
                        class="mt-2"
                        v-model="filterValue.billingpostcode"
                        label="Billing Postcode"
                    />

                    <VTextField
                        class="mt-2"
                        v-model="filterValue.billingcity"
                        label="Billing City"
                    />
                    
                    <VTextField
                        class="mt-2"
                        v-model="filterValue.billingstate"
                        label="Billing State"
                    />

                </VExpansionPanelText>
            </VExpansionPanel>

        </VExpansionPanels>
    </div>
</template>

<template>
    <VCard>
        <VCardItem>
            <VCardTitle>Campaign Detail</VCardTitle>
            <template #append>
                <VBtn 
                    class="mr-4 my-4"
                    color="error"
                    @click="navigateMain">
                    Back to Main
                </VBtn>
                <VBtn @click="openSubmitDialog">
                    Submit
                </VBtn>
            </template>
        </VCardItem>
        <VCardText class="pl-8">
            <VRow>                
                <VCol 
                    cols="12">
                    <div class="font-italic">
                        <b>Campaign Detail ID: </b> <span class="mx-2 text-primary"> #{{ detail.id }}</span>
                        <b>Campaign Reference Number: </b> <span class="ml-2 text-primary"> #{{ detail.ref_no }}</span>
                    </div>
                </VCol>
            </VRow>
            <VRow>
                <VCol 
                    cols="4" 
                    v-for="(item, idx) in tableHeader" 
                    :key="idx"
                    v-if="detail != undefined"
                    class="d-flex justify-between">
                    <div class="font-weight-bold text-sm">{{item.title }}:</div> 
                    <div>{{ 
                      detailItemValue(detail, item.key)
                    }}</div>
                </VCol>
            </VRow>
            <VDivider class="my-6" />
            <VRow>                
                <VCol 
                    cols="12" >
                    <div class="font-italic font-weight-bold">
                        Update Status
                    </div>
                </VCol>
            </VRow>
            <VRow>
                <VCol 
                    cols="6" >
                    <AppSelect
                        v-model="detail.progressStatus"
                        :items="categories"
                        label="Progress Status"
                    />
                </VCol>
                <VCol 
                    cols="6" >
                    <AppSelect
                        v-model="detail.progressSubStatus"
                        :items="sub_categories"
                        label="Progress Sub Status"
                    />
                </VCol>
                <VCol 
                    cols="12" >
                    <AppTextarea
                        v-model="detail.campaignAgentRemark"
                        label="Campaign Agent Remark"
                    />
                </VCol>
            </VRow>
            <VCardText class="d-flex justify-end flex-wrap gap-3">
                <VBtn @click="update">
                    Update
                </VBtn>
            </VCardText>
            <VSnackbar
                v-model="isError"
                :timeout="1000"
                location="top end"
                color="error"
            >
                {{ error }}
            </VSnackbar>
            <VSnackbar
                v-model="isSuccess"
                :timeout="1000"
                location="top end"
                color="success"
            >
                Status has been successfully updated.
            </VSnackbar>
        </VCardText>
        <SubmitDialog 
            :isDialogVisible="isDialogVisible"
            @close-dialog="closeSubmitDialog"
            @update-record="updateDetailRefNumber"
        />
    </VCard>
</template>
<script setup>
import SubmitDialog from '@/components/campaign-detail/SubmitDialog.vue';
import { tableHeaders } from '@/plugins/constant';
import axios from '@axios';

const router = useRouter()
const route = useRoute()
const id = parseInt(route.params.id);
const detail = ref({})
const isDialogVisible = ref(false)

const categories = ref([])
const all_sub_categories = ref([])

const isSuccess = ref(false)
const isError = computed(() => error.value.length > 0)
const error = ref('')

const tableHeader = tableHeaders.campaignDetail.filter(item => item.key != 'id' && item.key != 'campaignAgentRemark')

const detailItemValue = (detail, key) => {
  if (key == 'assigned_leader')
    return detail['leader']?.name
  else if (key == 'assigned_agent')
    return detail['agent']?.name
  else
  return detail[key] || '--' 
}

const props = defineProps({
  date_filter: {
    type: String,
    required: false
  }
})

const openSubmitDialog = () => {
    isDialogVisible.value = true
}

const closeSubmitDialog = () => {
    isDialogVisible.value = false
}

const convertCategory = (_categories) => _categories.map(item => ({
  value: item.id, 
  title: item.name,
  parent: item.parent_id
}))

const sub_categories = computed(()=> {
    const category = detail.value.progressStatus
    const sub_category = detail.value.progressSubStatus
    if (category == undefined)
        return []

    const arr = all_sub_categories.value.filter(item => item.parent == category)

    if (sub_category) {
        const subIdx = arr.findIndex(item => item.value == sub_category)
        if (subIdx == -1)
            detail.value.progressSubStatus = ""
    }

    return arr
})

const navigateMain = () => {
    router.back()
}

const getCategories = () => {
    axios.get('/admin/progress-categories')
        .then(res => {
            const { data } = res.data
            categories.value = convertCategory(data.categories)
            all_sub_categories.value = convertCategory(data.sub_categories)
            getCampaignDetailInfo()
        })
}

const getCampaignDetailInfo = () => {
    axios.get(`/campaign-detail/${id}`)
        .then(res => {
            const { campaign_detail } = res.data

            const categoryItem = categories.value.find(item => item.title == campaign_detail.progressStatus)
            const subCategoryItem = all_sub_categories.value.find(item => item.title == campaign_detail.progressSubStatus)
            detail.value = {
                ...campaign_detail, 
                progressStatus: categoryItem == null? '' : categoryItem.value, 
                progressSubStatus: subCategoryItem == null? '' : subCategoryItem.value, 
            }            
        })
}

const update = () => {
    let {progressStatus, progressSubStatus, campaignAgentRemark } = detail.value
    if (!progressStatus || progressStatus == '') {
        error.value = "Progress Status must not be empty!"
        return 
    }         

    if (!progressSubStatus || progressSubStatus == '') {
        error.value = "Progress Sub Status must not be empty!"
        return
    } 

    // if (!campaignAgentRemark || campaignAgentRemark == '') {
    //     error.value = "Progress Sub Status must not be empty!"
    //     return
    // }
    
    progressStatus = categories.value.find(item => item.value == progressStatus).title
    progressSubStatus = sub_categories.value.find(item => item.value == progressSubStatus).title

    try {
        
        axios.put(`/campaign-detail/${id}/status`, {
            progressStatus, 
            progressSubStatus, 
            campaignAgentRemark
        }).then((res) => {
          const { status } = res.data
          if (status == 'success') {
              isSuccess.value = true
          }
          
          if (router.options.history.state.back)
            router.push(router.options.history.state.back);
        })        
        
    } catch (error) {
        console.error('Error occurred:', error);
    }
        
}

const updateDetailRefNumber = (ref_no) => {
    isDialogVisible.value = false
    detail.value.ref_no = ref_no
    axios.put(`/campaign-detail/${id}/ref-number`, {
        ref_no
    })
        .then(res => {
            const { status } = res.data
            if (status == 'success') {
                isSuccess.value = true
            }
        })
}

onMounted(() => {
    getCategories()
})

</script>

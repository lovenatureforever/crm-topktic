<script setup>
import axios from '@axios';
import CustomFilter from './CustomFilter.vue';
import FilterTabs from './FilterTabs.vue';
import FilteredList from './FilteredList.vue';

const props = defineProps({
  campaign_id: {
    type: Number,
    required: true
  },
})

const filterList = ref({})
const status = ref("Default")
const sub_status = ref("")
const isNavDrawerOpen = ref(false)
const isCustom = ref(false)
const filter_param = ref({})

const getCampaignDetailList = (param) => {

  axios.get(`/admin/campaigns/${props.campaign_id}/filters`, {
    status: status.value,
    sub_status: sub_status.value,
    ...param 
  })
    .then(res => {
      const _filterdList = []
      const { counts_status, counts_substatus } = res.data

      let default_count, submission_count;

      for (var status of counts_status) {
        const key = status.progressStatus == '' ? 'Default' : status.progressStatus

        const items = {
          'All': status.count
        }
        if (key == 'Default') {

          if (status.count != 0) {
            default_count = {key, items}
          }
            
        } else if (key == 'submission') {
          
          if (status.count != 0) {
            submission_count = {key, items}
          }            
        
        } else {
          counts_substatus.filter(substatus => substatus.progressStatus == status.progressStatus)
            .forEach(substatus => {
              items[substatus.progressSubStatus] = substatus.count
            });

          _filterdList.push({key, items})
        } 
      }

      if (default_count) {
        _filterdList.unshift(default_count)
      }

      if (submission_count) {
        _filterdList.push(submission_count)
      }

      filterList.value = _filterdList
    })
}

const setFilter = (filter) => {
  if (status.value != filter.status || sub_status.value != filter.sub_status) {
    status.value = filter.status
    sub_status.value = filter.sub_status    
    isCustom.value = false
  }  
}

const openFilterNavigation = () => {
  isNavDrawerOpen.value = true
}

const closeFilterNavigation = () => {
  isNavDrawerOpen.value = false
}

const applyCustomFilter = (param) => {
  if (
    param.applicantname.length == 0 && 
    param.applicantidentity.length == 0 &&
    (!param.start_date || param.start_date.length == 0) &&   
    (!param.end_date || param.end_date.length == 0)
  ) {
    isCustom.value = false
    filter_param.value = {}
  } else {
    isCustom.value = true
    filter_param.value = param
  }
    
}

onMounted(() => {
  getCampaignDetailList()
})

</script>
<template>
    <div>
        <FilterTabs 
          v-if="Object.keys(filterList).length > 0" 
          :filterList="filterList"
          :status = "status"
          :sub_status="sub_status"
          :is_custom="isCustom"
          @set-custom-filter="openFilterNavigation"
          @set-filter="setFilter" />

        <FilteredList 
          class="mt-2" 
          :campaign_id="props.campaign_id"
          :status = "status"
          :sub_status="sub_status"
          :is_custom="isCustom"
          :custom_filter="filter_param" />

        <CustomFilter 
          :isNavDrawerOpen="isNavDrawerOpen"
          @close-custom-filter="closeFilterNavigation"
          @apply-custom-filter="applyCustomFilter" />
    </div>
</template>

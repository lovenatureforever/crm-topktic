<script setup>
import axios from '@axios';
import { useRoute } from 'vue-router';
import { VDataTable } from 'vuetify/labs/VDataTable';

const headers = ref([])
const data = ref([])
const count_headers = ref([])
const route = useRoute()
const campaignId = route.params.id;
const dateRange = ref('')

const getLeadProgress = () => {
  const param = {}
  if (dateRange.value !== '') {
    let [start, end] = dateRange.value.split(" to ");
    if (!end || end == '')
      end = start
    param.start_date = start
    param.end_date = end
  } else {
    const today = new Date().toISOString().substring(0, 10)
    param.start_date = today
    param.end_date = today
  }
  
  axios.post(`/campaign-detail/${campaignId}/lead-progress`, param)
  .then(res => {
    const {status, lead_progress} = res.data

    const headerValue = [
      {key: "name", title: "Name"},
      // {key: "total_leads", title: "Total Leads"},
      {key: "filtered_total_leads", title: "Filtered Total Leads"},
    ]

    const countHeaderValue = []
    for (const state of status) {
      countHeaderValue.push({key: state, title: state == '' ? 'Fresh' : state.toUpperCase()})
    }
    count_headers.value = countHeaderValue
    headers.value = [...headerValue,  ...countHeaderValue]
    data.value = lead_progress
    
  })
}

const formatField = (count, total, isAverage) => {
  if (total == 0 || count == 0)
    return 0;  

  if (isAverage)
    return count  

  return `${count}`
}

const applyFilter = () => {
  getLeadProgress();
}

onMounted(() => {
    getLeadProgress();
});

</script>

<template>
    <div>
        <div class="v-card-title">Filtered Accumulated Data</div>
        <div class="d-flex mb-2 mx-4 ">
            <small>Filter: </small>
        </div>
        <div class="d-flex mx-4 mb-4 align-center">
            
            <AppDateTimePicker
                class="mr-4 date-picker"
                v-model="dateRange"
                :config="{ mode: 'range' }"
            />

            <VBtn @click="applyFilter">
                Apply
            </VBtn>
        </div>
        <VDataTable
            :headers="headers"
            :items="data"
            :itemsPerPage="50">
            <template v-for="header in count_headers" #[`item.${header.key}`] ="{ item }">
              <!-- {{ formatField(item.raw[header.key], item.raw["total_leads"], item.raw['name'] == 'Average') }} -->
              {{ item.raw[header.key] }}
            </template>
        </VDataTable>
    </div>
</template>
<style>
.date-option {
    width: 240px !important;
    flex: none !important;
}
.date-picker {
    width: 240px !important;
}
</style>

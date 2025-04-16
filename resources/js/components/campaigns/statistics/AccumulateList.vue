<script setup>
import axios from '@axios';
import { useRoute } from 'vue-router';
import { VDataTable } from 'vuetify/labs/VDataTable';

const headers = ref([])
const data = ref([])
const count_headers = ref([])
const route = useRoute()
const campaignId = route.params.id;

const getLeadProgress = () => {
  
  axios.post(`/campaign-detail/${campaignId}/lead-progress`)
  .then(res => {
    const {status, lead_progress} = res.data

    const headerValue = [
      {key: "name", title: "Name"},
      {key: "total_leads", title: "Total Leads"},
      {key: "filtered_total_leads", title: "Filtered Total Leads"},
    ]

    const countHeaderValue = [{key: '', title: 'Fresh'}]
    for (const state of status) {
      if (state != '')
        countHeaderValue.push({key: state, title: state})
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
    return `${(100 * count / total).toFixed(2)}%`  

  return `${count} (${(100 * count / total).toFixed(2)}%)`
}

onMounted(() => {
    getLeadProgress();
});

</script>

<template>
    <div>
        <div class="v-card-title">Accumulated Data</div>
        <VDataTable
            :headers="headers"
            :items="data"
            :itemsPerPage="50">
            <template v-for="header in count_headers" #[`item.${header.key}`] ="{ item }">
              {{ formatField(item.raw[header.key], item.raw["total_leads"], item.raw['name'] == 'Average') }}
            </template>
        </VDataTable>
    </div>
</template>

<template>
  <VDataTableServer
    :headers="tableHeader"
    :items="data"
    :items-per-page="options.itemsPerPage"
    :loading="loading"
    fixed-header
    height="600"
    density="default"
    @click:row="goToDetail"
  >
    <template
      v-if="isNotAgent"
      #item.assigned_leader="{ item }"
    >
      {{ item.raw.leader?.name }}
    </template>

    <template
      v-if="isNotAgent"
      #item.assigned_user="{ item }"
    >
      {{ item.raw.agent?.name }}
    </template>
    <template #bottom>
      <VCardText class="mt-8">
        <VRow>
          <!--
            <VCol lg="2" cols="3">
            <VTextField v-model="options.itemsPerPage" label="Rows per page:" type="number" min="-1" max="15"
            hide-details />
            </VCol>
          -->
          <VSelect
            v-model="options.itemsPerPage"
            :items="[5, 10, 25, 50, 100]"
            label="Rows per page:"
            variant="underlined"
            style="max-inline-size: 8rem;min-inline-size: 5rem;"
            @update:model-value="itemsPerPageChanged"
          />

          <VCol
            lg="10"
            cols="9"
            class="d-flex justify-end"
          >
            <VPagination
              v-model="options.page"
              total-visible="5"
              :length="totalPages"
            />
          </VCol>
        </VRow>
      </VCardText>
    </template>
  </VDataTableServer>
</template>

<!-- eslint-disable vue/prop-name-casing -->
<script setup>
import { isAdminOrTeamLeader } from "@/plugins/auth"
import { tableHeaders, tableOption } from '@/plugins/constant'
import axios from '@axios'
import { VDataTableServer } from 'vuetify/labs/VDataTable'

const props = defineProps({
  showSelect: {
    type: Boolean,
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
  custom_filter: {
    type: Object,
    required: false,
  },
  campaign_id: {
    type: Number,
    required: true,
  },
  is_custom: {
    type: Boolean,
    required: true,
  },
})

const router = useRouter()
const loading = ref(false)
const options = ref({ ...tableOption, page: Number(localStorage.getItem('page'))||1, itemsPerPage: Number(localStorage.getItem('itemsPerPage'))||50 })
const totalPages = ref(0)
const status = toRef(props, 'status')
const sub_status = toRef(props, 'sub_status')
const is_custom = toRef(props, 'is_custom')
const data = ref([])
const isNotAgent = isAdminOrTeamLeader()

const tableHeader = isNotAgent ?
  tableHeaders.campaignTable :
  tableHeaders.campaignTable.filter(hItem => hItem.key != 'assigned_leader' && hItem.key != 'assigned_user')

const goToDetail = (evt, row) => {
  router.push(`/campaign-detail/${row.item.raw.id}`)
}

const fetchPaginatedData = async () => {
  loading.value = true

  const param = {
    status: status.value,
    sub_status: sub_status.value,
    ...props.custom_filter,
  }

  axios.post(
    `/admin/campaigns/${props.campaign_id}/list?page=${options.value.page}&itemsPerPage=${options.value.itemsPerPage}`,
    param,
  ).then(res => {
    const { total, list } = res.data

    data.value = list
    totalPages.value = Math.ceil(total / options.value.itemsPerPage)
    loading.value = false
    localStorage.setItem('itemsPerPage', options.value.itemsPerPage)
    localStorage.setItem('page', options.value.page)
  }).catch(error => {
    console.error('Error fetching data:', error)
    loading.value = false
  })

}

watch([
  () => options.value.page,
], () => {
  fetchPaginatedData()
})

const itemsPerPageChanged = value => {
  if (options.value.page == 1)
    fetchPaginatedData()
  else
    options.value.page = 1
}

watch([
  status,
  sub_status,
  is_custom,
  () => props.custom_filter,
], () => {
  options.value.page = 1
  fetchPaginatedData()
})

onMounted(() => {
  fetchPaginatedData()
})
</script>

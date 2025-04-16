<script setup>
import CreateDialog from '@/components/campaigns/CreateDialog.vue';
import ImportDialog from '@/components/campaigns/ImportDialog.vue';
import { isAdmin } from '@/plugins/auth';
import { ALL, defaultCampaign, filter_user_states, tableHeaders, tableOption } from '@/plugins/constant';
import axios from '@axios';
import { VDataTable } from 'vuetify/labs/VDataTable';

const campaignList = ref([])
const filteredCampaignList = ref([])

const options = ref(tableOption)
const showDialog = ref(false)
const deleteDialog = ref(false)
const importDialog = ref(false)
const isUpdate = ref(false)

const campaign = ref(defaultCampaign)
const category = ref()
const sub_category = ref()
const status = ref()

const categories = ref([])
const sub_categories = ref([])
const deletedCampaignId = ref(-1)
const importCampaign = ref({})

const no_filter = {
  value: -1, 
  title: ALL,
  parent: -1
}

const filtered_subcategories = computed(()=> {
  if (category.value == undefined)
    return []

  return sub_categories.value.filter(item => item.parent == category.value)
})

const filter_categories = computed(() => {
  return [no_filter, ...categories.value]
})

const filter_sub_categories = computed(() => {
  sub_category.value = no_filter.value
  return [no_filter, ...filtered_subcategories.value]
})

const resolveStatusVariant = _status => {
  if (_status === 'active')
    return {
      color: 'primary',
      text: 'Active',
    }
  else if (_status === 'inactive')
    return {
      color: 'secondary',
      text: 'Inactive',
    }
  else {
    return {
      color: 'secondary',
      text: 'Inactive',
    }
  }
  
}

const convertCategory = (_categories) => _categories.map(item => ({
  value: item.id, 
  title: item.name,
  parent: item.parent_id
}))

const getCategory = (id) => categories.value.find(item => item.value == id)
const getSubCategory = (id) => sub_categories.value.find(item => item.value == id)

const openCreateDialog = () => {
    showDialog.value = true
    isUpdate.value = false
}

const closeCreateDialog = (changed) => {
  showDialog.value = false
  campaign.value = defaultCampaign
  if (changed) {
      getCampaignList()
  }
}

const editCampaignDialog = (item) => {
  campaign.value = item
  showDialog.value = true
  isUpdate.value = true
}

const deleteCampaignDialog = (id) => {
  deleteDialog.value = true
  deletedCampaignId.value = id
}

const closeDeleteDialog = () => {
    deleteDialog.value = false
    deletedCampaignId.value = -1
}

const importCampaignDataDialog = (item) => {
  importDialog.value = true
  importCampaign.value = item
}

const closeImportDialog = () => {
    importDialog.value = false
}


const activate = (id) => {
  axios.put(`/admin/campaigns/activate/${id}`)
  .then(res => {
    const {campaigns} = res.data
    campaignList.value = campaigns
    filteredCampaignList.value = campaigns
  })
}

const deleteItemConfirm = () => {
    axios.delete(`/admin/campaigns/${deletedCampaignId.value}`)
        .then((res) => {
            const { campaigns } = res.data
            campaignList.value = campaigns
            
            deleteDialog.value = false
            deletedCampaignId.value = -1
        })    
}

const getCampaignList = () => {
  axios.get('/admin/campaigns')
    .then(res => {
      const {campaigns} = res.data
      campaignList.value = campaigns
      filteredCampaignList.value = campaigns
    })
}

const getCategories = () => {
  axios.get('/admin/campaign-categories')
    .then(res => {
        const { data } = res.data
        categories.value = convertCategory(data.categories)
        sub_categories.value = convertCategory(data.sub_categories)

        category.value = no_filter.value
        status.value = ALL
    })
}

const applyFilter = () => {
  const _category = category.value
  const _sub_category = sub_category.value
  const _status = status.value
  let _filterdList = [...campaignList.value]
  
  if (_category != -1) {
    _filterdList = _filterdList.filter(item => item.category == getCategory(_category).title)   
  
    if (_sub_category != -1) {
      _filterdList = _filterdList.filter(item => item.sub_category == getSubCategory(_sub_category).title)
    }
  } 
  
  if (_status != ALL) {
    _filterdList = _filterdList.filter(item => item.status == _status)
  } 

  filteredCampaignList.value = _filterdList
}

onMounted(() => {
  getCategories();
  getCampaignList();
})
</script>

<template>
  <div>
    <VCard>
      <VCardItem>
        <VCardTitle>Campaign List</VCardTitle>
        <template v-if="isAdmin()" #append>
          <VBtn 
            size="small" 
            icon="tabler-plus" 
            @click="openCreateDialog"
          />
        </template>
      </VCardItem>
      <VCardText>
        <div class="mb-2">
          Filter:
        </div>
        <VRow class="mb-3 mx-4">
          <VCol>
            <VSelect
              :items="filter_categories"
              v-model="category"
              label="Category"
              />
          </VCol>
          <VCol>
            <VSelect
              :items="filter_sub_categories"
              v-model="sub_category"
              label="Sub Category"
            />
          </VCol>
          <VCol>
            <VSelect
              :items="filter_user_states"
              v-model="status"
              label="Status"
            />
          </VCol>
          <VCol>
            <VBtn
              block
              @click="applyFilter"
            >
            Apply
            </VBtn>
          </VCol>
        </VRow>
        <VDataTable
          :headers="tableHeaders.campaign"
          :items="filteredCampaignList"
          :items-per-page="options.itemsPerPage"
          :page="options.page"
          @update:options="options = $event"
        >
          <template #item.name="{ item }">
            <RouterLink
              :to="`/campaigns/${item.raw.id}`"
              class="link d-flex align-center gap-x-3"
            >
              {{ item.raw.name }}
            </RouterLink>
          </template>

          <template #item.status="{ item }">
            <VChip
              :color="resolveStatusVariant(item.raw.status).color"
              @click="activate(item.raw.id)"
              class="font-weight-medium"
              size="small"
            >
              {{ resolveStatusVariant(item.raw.status).text }}
            </VChip>
          </template>

          <template #item.actions="{ item }">
            <div class="d-flex gap-1">
                <IconBtn @click="editCampaignDialog(item.raw)">
                    <VIcon icon="mdi-pencil-outline" />
                </IconBtn>
                <IconBtn @click="importCampaignDataDialog(item.raw)">
                    <VIcon icon="tabler-arrow-big-up-lines" />
                </IconBtn>
                <IconBtn @click="deleteCampaignDialog(item.raw.id)">
                    <VIcon icon="mdi-delete-outline" />
                </IconBtn>
            </div>
          </template>

          <template #bottom>
            <VCardText class="mt-8">
              <VRow>
                <VCol
                  lg="2"
                  cols="3"
                >
                  <VTextField
                    v-model="options.itemsPerPage"
                    label="Rows per page:"
                    type="number"
                    min="-1"
                    max="15"
                    hide-details
                  />
                </VCol>

                <VCol
                  lg="10"
                  cols="9"
                  class="d-flex justify-end"
                >
                  <VPagination
                    v-model="options.page"
                    total-visible="5"
                    :length="Math.ceil(campaignList.length / options.itemsPerPage)"
                  />
                </VCol>
              </VRow>
            </VCardText>
          </template>
        </VDataTable>
      </VCardText>
    </VCard>
    <CreateDialog 
      :isDialogVisible="showDialog"
      :campaign="campaign"
      :categories="categories"
      :sub_categories="sub_categories"
      :isUpdate="isUpdate"
      @close-dialog="closeCreateDialog"
      />

    <ImportDialog 
      :isDialogVisible="importDialog"
      :campaign="importCampaign"
      @close-dialog="closeImportDialog"
      />
    

    <VDialog
      v-model="deleteDialog"
      max-width="500px">
      <VCard>
          <VCardTitle class="px-8 py-8">
              Are you sure you want to delete this campaign?
          </VCardTitle>

          <VCardActions>
              <VSpacer />

              <VBtn
                  color="error"
                  variant="outlined"
                  @click="closeDeleteDialog"
              >
                  Cancel
              </VBtn>

              <VBtn
                  color="success"
                  variant="elevated"
                  @click="deleteItemConfirm"
              >
                  OK
              </VBtn>

              <VSpacer />
          </VCardActions>
      </VCard>
    </VDialog>
  </div>
</template>
<style>
.link {
  color: rgb(var(--v-theme-on-background));
}
</style>
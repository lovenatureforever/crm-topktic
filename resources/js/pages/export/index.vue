<template>
  <div>
    <VCard>
      <VCardItem>
        <VCardTitle>Export CSV</VCardTitle>
      </VCardItem>
      <VCardText>
        <div class="mx-4">
          <VSelect class="mt-4" v-model="teamleader" :items="teamleaders" variant="outlined" label="Teamleader" />

          <VSelect class="mt-4" v-model="agent" :items="agentsUnderLeader" variant="outlined" label="Agent" />

          <VSelect class="mt-4" v-model="category" :items="categories" variant="outlined" label="Category" />

          <VSelect class="mt-4" v-model="subCategory" :items="subCategoriesUnderCategory" variant="outlined"
            label="Sub-category" />

          <VBtn class="mt-4" @click="applyFilter">
            Download
          </VBtn>

        </div>
      </VCardText>
    </VCard>
  </div>
</template>

<script setup>
import axios from '@axios'
import { onMounted, ref, watch } from 'vue'

const teamleaders = ref([])
const agents = ref([])
const agentsUnderLeader = ref([])
const categories = ref([])
const subCategories = ref([])
const subCategoriesUnderCategory = ref([])

const teamleader = ref("")
const agent = ref("")
const category = ref("")
const subCategory = ref("")

const applyFilter = () => {
  // console.log(`/export-csv?teamleader=${teamleader.value}&agent=${agent.value}&category=${category.value}&sub-category=${subCategory.value}`)
  window.open(`/api/export-csv?leader=${teamleader.value}&agent=${agent.value}&status=${category.value}&sub-status=${subCategory.value}`, '_blank')
}

const getList = () => {
  axios.get('/users')
    .then((res) => {
      const { users } = res.data
      teamleaders.value = users.filter(user => user.role == "Team Leader").map(leader => {
        return {
          value: leader.id,
          title: leader.username,
        }
      })
      agents.value = users.filter(user => user.role == "Agent").map(leader => {
        return {
          leader: leader.team_leader,
          value: leader.id,
          title: leader.username,
        }
      })
    });

  axios.get('/admin/progress-categories')
    .then((res) => {
      categories.value = res.data.data.categories.map(category => {
        return {
          id: category.id,
          value: category.name,
          title: category.name,
        }
      })
      subCategories.value = res.data.data.sub_categories.map(subCategory => {
        return {
          parent: subCategory.parent_id,
          value: subCategory.name,
          title: subCategory.name,
        }
      })
    });
}

onMounted(() => {
  getList()
})

watch(teamleader, (newValue) => {
  agentsUnderLeader.value = agents.value.filter(agent => agent.leader == newValue)
  agentsUnderLeader.value.unshift({ value: "all", title: "All" })
  agent.value = "all"
})

watch(category, (newValue) => {
  const category_id = categories.value.find(category => category.value == newValue).id
  subCategoriesUnderCategory.value = subCategories.value.filter(subCategory => subCategory.parent == category_id)
  subCategoriesUnderCategory.value.unshift({ value: "all", title: "All" })
  subCategory.value = "all"
})
</script>

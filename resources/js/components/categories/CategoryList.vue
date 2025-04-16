<template>
    <div>
    <VRow>
        <VCol cols="12" md="1" />
        <VCol cols="6" md="5">
            <div class="flat-card">
                <div class="d-flex w-full align-center justify-between mb-2">
                    <div class="v-card-title">
                        Categories
                    </div>
                    <VBtn 
                        size="small" 
                        icon="tabler-plus" 
                        @click="createCategoryDialog(true)"
                    />
                    
                </div>
                <VDivider />
                <div>
                    <div
                        v-for="(item, index) in categories"
                        :key="index" 
                        class="d-flex w-full align-center justify-between mb-2 px-3 pt-2">
                        <div 
                            class="cursor-pointer w-full"
                            :class="selected_category == item.id? 'text-success' : ''"
                            @click="showCategory(item.id)">
                            {{ item.name }}
                        </div>
                        
                        <div class="d-flex gap-1">
                            <IconBtn @click="editCategoryDialog(item)">
                                <VIcon icon="mdi-pencil-outline" />
                            </IconBtn>
                            <IconBtn @click="deleteCategoryDialog(item.id)">
                                <VIcon icon="mdi-delete-outline" />
                            </IconBtn>
                        </div>
                    </div>
                </div>
            </div>
        </VCol>
        <VCol cols="6" md="5">
            <div class="flat-card">
                <div class="d-flex w-full align-center justify-between mb-2">
                    <div class="v-card-title">
                        Sub Categories 
                    </div>
                    <VBtn 
                        size="small" 
                        icon="tabler-plus" 
                        @click="createCategoryDialog(false)"
                    />
                </div>
                <VDivider />
                <div>
                    <div
                        v-for="(item, index) in categories_by_parent"
                        :key="index" 
                        class="d-flex w-full align-center justify-between px-2 pt-2">
                        <span>
                            {{ item.name }}
                        </span>
                        <div class="d-flex gap-1">
                            <IconBtn @click="editCategoryDialog(item)">
                                <VIcon icon="mdi-pencil-outline" />
                            </IconBtn>
                            <IconBtn @click="deleteCategoryDialog(item.id)">
                                <VIcon icon="mdi-delete-outline" />
                            </IconBtn>
                        </div>
                    </div>
                </div>
            </div>
        </VCol>
        <VCol cols="12" md="1" />
    </VRow>
    <VDialog
        v-model="createDialog"
        max-width="720">
        <!-- Dialog close btn -->
        <DialogCloseBtn @click="closeCreateDialog" />

        <!-- Dialog Content -->
        <VCard title="Campaign Category">
            <VCardText>
                <VTextField
                    class="mb-4"
                    v-model="editCategoryItem.name"
                    label="Category Name"
                />       
                
                <VSelect
                    class="mb-4"
                    v-model="parentItem"
                    :items="categories_selector"
                    variant="outlined"
                    label="Parent Category"
                />

            </VCardText>

            <VCardText class="d-flex justify-end flex-wrap gap-3">
                <VBtn
                    variant="tonal"
                    color="secondary"
                    @click="closeCreateDialog"    
                >
                    Close
                </VBtn>
                <VBtn @click="saveCategory">
                    Save
                </VBtn>
            </VCardText>
        </VCard>
    </VDialog>
    <VDialog
        v-model="deleteDialog"
        max-width="500px">
        <VCard>
            <VCardTitle class="px-8 py-8">
                Are you sure you want to delete this category?
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
<script setup>
import { defaultCategory } from '@/plugins/constant';
import axios from '@axios';
import { computed, onMounted } from 'vue';

const props = defineProps({
    baseUrl: {
        type: String,
        required: true
    }
})

const main_category = {
    value: -1,
    title: "Main"
}
const categories = ref([])
const sub_categories = ref([]) 

const selected_category = ref(-1);
const deleted_category = ref(-1);
const createDialog = ref(false)
const deleteDialog = ref(false)

const categories_by_parent = computed(() => sub_categories.value.filter(item => item.parent_id == selected_category.value))
const categories_selector = computed(() => {
    
    return [
        main_category, 
        ...categories.value.map(
            (item) => ({
                value: item.id, 
                title: item.name
            })
        )
    ]
} )

const editCategoryItem = ref(defaultCategory)
const parentItem = ref(main_category)

const showCategory = (id) => {
    selected_category.value = id
}

const createCategoryDialog = (isMain) => {
    if (!isMain) {
        const sItem = categories_selector.value.find(item => item.value == selected_category.value);
        parentItem.value = sItem ? sItem.value : -1;
    }        
    else
        parentItem.value = -1

    editCategoryItem.value = defaultCategory
    createDialog.value = true
}

const editCategoryDialog = (item) => {
    editCategoryItem.value = item
    createDialog.value = true
}

const deleteCategoryDialog = (id) => {
    deleted_category.value = id
    deleteDialog.value = true
}

const closeCreateDialog = () => {
    createDialog.value = false;
}

const closeDeleteDialog = () => {
    deleteDialog.value = false
}

const saveCategory = () => {
    const category = { ...editCategoryItem.value }
    const category_id = category.id
    const parent = parentItem.value
    delete category.id

    if (!parent) {
        return
    }

    const pitem = categories_selector.value.find(item => item.value == parent)
    if (pitem == null || pitem.value == -1) {
        delete category.parent_id
    } else {
        category.parent_id = pitem.value
    }

    if (category_id == -1) {    
        axios.post(props.baseUrl, category)
            .then(res => {
                const { data } = res.data
                categories.value = data.categories
                sub_categories.value = data.sub_categories

                createDialog.value = false
                showCategory(parent)
            })
    } else {
        axios.put(`${props.baseUrl}/${category_id}`, category)
            .then(res => {
                const { data } = res.data
                categories.value = data.categories
                sub_categories.value = data.sub_categories

                createDialog.value = false
                showCategory(parent)
            }) 
    }
        
}

const deleteItemConfirm = () => {
    axios.delete(`${props.baseUrl}/${deleted_category.value}`)
            .then(res => {
                const { data } = res.data
                categories.value = data.categories
                sub_categories.value = data.sub_categories

                deleted_category.value = -1
                deleteDialog.value = false
            })
}

onMounted(() => {
    axios.get(props.baseUrl)
        .then(res => {
            const { data } = res.data
            categories.value = data.categories
            sub_categories.value = data.sub_categories

            if (data.categories.length > 0)
                selected_category.value = data.categories[0].id
        })
})

</script>

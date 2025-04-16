<template>
    <div>
        <VCard>
            <VCardItem>
                <VCardTitle>Campaign Categories List</VCardTitle>
            </VCardItem>
            <VCardText>
                <CategoryList baseUrl="/admin/campaign-categories" />
            </VCardText>
        </VCard>

        <VCard class="mt-4">
            <VCardItem>
                <VCardTitle>Progress Status List</VCardTitle>
            </VCardItem>
            <VCardText>
                <CategoryList baseUrl="/admin/progress-categories" />
            </VCardText>
        </VCard>
    </div>
</template>
<script setup>
import CategoryList from '@/components/categories/CategoryList.vue';



</script>
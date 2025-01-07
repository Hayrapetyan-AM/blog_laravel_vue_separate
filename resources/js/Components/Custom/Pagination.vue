<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    blogs: Array | Object,
});

const goToPage = (url) => {
    // Make a request to the next or previous page based on the URL
    router.get(url);
};
</script>

<template>
    <!-- Pagination controls -->
    <div class="mt-4 flex justify-center">
        <button
            v-if="blogs.prev_page_url"
            @click="goToPage(blogs.prev_page_url)"
            class="bg-primary-700 hover:bg-primary-800 rounded-lg px-4 py-2 text-white"
        >
            Previous
        </button>
        <button
            v-if="blogs.next_page_url"
            @click="goToPage(blogs.next_page_url)"
            class="bg-primary-700 hover:bg-primary-800 ml-4 rounded-lg px-4 py-2 text-white"
        >
            Next
        </button>
    </div>
</template>

<style scoped></style>

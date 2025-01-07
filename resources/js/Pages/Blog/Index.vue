<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Pagination from '@/Components/Custom/Pagination.vue';
import BlogSmall from '@/Components/Custom/BlogSmall.vue';

const props = defineProps({
    blogs: Array | Object,
    user : Object,
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Here you can see your blogs
            </h2>
        </template>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    v-for="blog in blogs.data"
                    :key="blog.id"
                    class="mb-2 overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <BlogSmall :blog="blog" :isAuthor="Number(user.id) === Number(blog.user_id)"></BlogSmall>
                </div>

                <Pagination :blogs="blogs" ></Pagination>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

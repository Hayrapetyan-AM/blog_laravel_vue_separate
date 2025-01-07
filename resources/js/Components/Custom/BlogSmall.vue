<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    blog: Object,
    isAuthor: Boolean,
});
const deleteForm = useForm({
    id: '',
});

const truncateText = (text, limit) => {
    if (text.length > limit) {
        return text.slice(0, limit) + '...';
    }
    return text;
};

const deleteBlog = (id) => {
    deleteForm.delete(route('blogs.destroy', { id: id }));
};
</script>

<template>
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <section class="bg-white dark:bg-gray-900">
            <div
                class="mx-auto grid max-w-screen-xl px-4 py-8 lg:grid-cols-12 lg:gap-8 lg:py-16 xl:gap-0"
            >
                <div class="mr-auto place-self-center lg:col-span-7">
                    <h1
                        class="mb-4 max-w-2xl text-4xl font-extrabold leading-none tracking-tight md:text-5xl xl:text-6xl dark:text-white"
                    >
                        {{ blog.title }}
                    </h1>
                    <p>Blog author: {{ blog?.user.name }}</p>
                    <p
                        class="mb-6 max-w-2xl font-light text-gray-500 md:text-lg lg:mb-8 lg:text-xl dark:text-gray-400"
                    >
                        {{ truncateText(blog.context, 250) }}
                    </p>
                    <div>
                        <a
                            :href="
                                route('blogs.show', {
                                    id: blog.id,
                                })
                            "
                            class="bg-primary-700 hover:bg-primary-800 focus:ring-primary-300 dark:focus:ring-primary-900 mr-3 inline-flex items-center justify-center rounded-lg px-5 py-3 text-center text-base font-medium text-green-600 focus:ring-4"
                        >
                            Read More
                        </a>

                        <div v-if="isAuthor" class="inline-flex">
                            <a
                                :href="
                                    route('blogs.edit', {
                                        id: blog.id,
                                    })
                                "
                                class="bg-primary-700 hover:bg-primary-800 focus:ring-primary-300 dark:focus:ring-primary-900 mr-3 inline-flex items-center justify-center rounded-lg px-5 py-3 text-center text-base font-medium text-orange-600 focus:ring-4"
                            >
                                Edit post
                            </a>

                            <a
                                href="#delete"
                                @click="deleteBlog(blog.id)"
                                class="bg-primary-700 hover:bg-primary-800 focus:ring-primary-300 dark:focus:ring-primary-900 mr-3 inline-flex items-center justify-center rounded-lg px-5 py-3 text-center text-base font-medium text-red-600 focus:ring-4"
                            >
                                Delete post
                            </a>
                        </div>
                    </div>
                </div>
                <div class="hidden justify-end lg:col-span-5 lg:mt-0 lg:flex">
                    <img
                        width="300"
                        :src="blog.image"
                        alt="Blog Image"
                        class="rounded-lg shadow-lg"
                    />
                </div>
            </div>
        </section>
    </div>
</template>

<style scoped></style>

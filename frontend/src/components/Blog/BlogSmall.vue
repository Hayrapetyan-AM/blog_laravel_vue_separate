<script setup>
import { useRouter } from 'vue-router';

const router = useRouter();

const viewBlog = (id) => {
  router.push({ name: 'blogView', params: { id : id } });
};

const editBlog = (id) => {
  router.push({ name: 'blogEdit', params: { id : id } });
};

const props = defineProps({
  blog: Object,
  isAuthor: Boolean,
})

const getImage = (imagePath) => {
  return import.meta.env.VITE_BASE_PATH + '/' +  imagePath;
}
const deleteForm = {
  id: '',
  async delete(url) {
    try {
      const response = await fetch(url, {
        method: 'DELETE',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      });

      if (response.ok) {
        // Ensure the response contains valid JSON (in case the body is empty)
        const responseData = await response.json().catch(() => ({})); // If JSON parsing fails, return an empty object
        console.log(responseData.message || 'Blog deleted successfully');
        window.location.reload()
      } else {
        // If not OK, handle error
        const errorData = await response.json().catch(() => ({})); // Prevent JSON parse error
        console.error('Error deleting blog:', errorData);
      }
    } catch (error) {
      console.error('Request failed:', error);
    }
  },
};



const truncateText = (text, limit) => {
  if (text.length > limit) {
    return text.slice(0, limit) + '...'
  }
  return text
}

const deleteBlog = (id) => {
  // Pass the correct URL to the deleteForm.delete method
  deleteForm.delete(`${import.meta.env.VITE_BASE_URL}/blogs/${id}`)
}
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
              @click="viewBlog(blog.id)"
              class="bg-primary-700 hover:bg-primary-800 focus:ring-primary-300 dark:focus:ring-primary-900 mr-3 inline-flex items-center justify-center rounded-lg px-5 py-3 text-center text-base font-medium text-green-600 focus:ring-4"
            >
              Read More
            </a>

            <div v-if="isAuthor" class="inline-flex">
              <a
                @click="editBlog(blog.id)"
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
          <img width="300" :src="getImage(blog.image)" alt="Blog Image" class="rounded-lg shadow-lg" />
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped></style>

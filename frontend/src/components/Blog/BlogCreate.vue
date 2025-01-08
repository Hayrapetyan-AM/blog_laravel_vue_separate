<script setup>
import { ref } from 'vue';

const title = ref('');
const context = ref('');
const image = ref('');
const formError = ref({});
const isLoading = ref(false);

const getError = (field) => formError.value[field]?.join(', ') || '';

const handleFileChange = (e) => {
  image.value = e.target.files[0];
};

const createBlog = async () => {
  isLoading.value = true;
  try {
    const formData = new FormData();
    formData.append('title', title.value);
    formData.append('context', context.value);
    formData.append('image', image.value);

    const response = await fetch('/api/blogs', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
      body: formData,
      credentials: 'include',
    });

    if (response.ok) {
      window.location.href = 'blog/show';
    } else {
      let resp = await response.json();
      formError.value = resp.errors;
      console.error('Error fetching blogs:', response.statusText);
    }
  } catch (error) {
    formError.value = { general: 'An unexpected error occurred. Please try again.' };
    console.error('Request failed:', error);
  } finally {
    isLoading.value = false;
  }
};
</script>

<template>
  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <form @submit.prevent="createBlog" class="mx-auto" enctype="multipart/form-data">
            <div class="blog-input mb-5">
              <label for="title" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                Blog title
              </label>
              <input
                v-model="title"
                type="text"
                id="title"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                placeholder="Enter blog title"
                required
                aria-describedby="title-error"
              />
              <div id="title-error" class="mt-1 text-xs text-red-500">{{ getError('title') }}</div>
            </div>

            <div class="blog-textarea mb-5">
              <label for="textarea" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                Your message
              </label>
              <textarea
                v-model="context"
                id="textarea"
                rows="4"
                class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                placeholder="Write your thoughts here..."
                aria-describedby="context-error"
              ></textarea>
              <div id="context-error" class="mt-1 text-xs text-red-500">{{ getError('context') }}</div>
            </div>

            <div class="blog-fileinput flex w-full items-center justify-center">
              <label
                for="dropzone-file"
                class="flex h-64 w-full cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 hover:bg-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:hover:border-gray-500 dark:hover:bg-gray-600"
              >
                <div class="flex flex-col items-center justify-center pb-6 pt-5">
                  <svg
                    class="mb-4 h-8 w-8 text-gray-500 dark:text-gray-400"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 20 16"
                  >
                    <path
                      stroke="currentColor"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"
                    />
                  </svg>
                  <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-semibold">Click to upload</span> or drag and drop
                  </p>
                  <p class="text-xs text-gray-500 dark:text-gray-400">
                    SVG, PNG, JPG or GIF (MAX. 800x400px)
                  </p>
                </div>
                <input @change="handleFileChange" id="dropzone-file" type="file" class="hidden" />
              </label>
              <div class="mt-1 text-xs text-red-500">{{ getError('image') }}</div>
            </div>

            <button
              type="button"
              @click="createBlog"
              :disabled="isLoading"
              class="mt-4 w-full rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 sm:w-auto dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            >
              {{ isLoading ? 'Submitting...' : 'Submit' }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
:where(.blog-textarea, .blog-fileinput, .blog-input) {
  margin-bottom: 20px;
}
</style>

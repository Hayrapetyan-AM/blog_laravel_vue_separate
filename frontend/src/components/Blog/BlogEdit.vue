<script setup>
import { ref, watch } from 'vue'

const formError = ref({})
const isLoading = ref(false)

const props = defineProps({
  blog: Object,
})

const getImage = (imagePath) => {
  return import.meta.env.VITE_BASE_PATH + '/' + imagePath
}

const oldImage = props.blog.image

// Watch props to keep form inputs updated
watch(() => props.blog, (newBlog) => {
  formError.value = {} // Clear errors when props change
})

const validateForm = () => {
  const errors = {}

  if (!props.blog.title || props.blog.title.trim() === '') {
    errors.title = 'Title is required.'
  }

  if (!props.blog.context || props.blog.context.trim() === '') {
    errors.context = 'Context is required.'
  }

  if (!props.blog.image) {
    errors.image = 'Please upload an image.'
  }

  formError.value = errors
  return Object.keys(errors).length === 0
}

const updateBlog = async () => {
  if (!validateForm()) {
    return
  }

  isLoading.value = true
  try {
    const formData = new FormData()
    formData.append('title', props.blog.title)
    formData.append('context', props.blog.context)
    formData.append('image', props.blog.image)
    formData.append('id', props.blog.id)

    const response = await fetch(`/api/blogs/${props.blog.id}`, {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
      body: formData,
      credentials: 'include',
    })

    if (response.ok) {
      window.location.href = '/'
    } else {
      const resp = await response.json()
      formError.value = resp.errors || { general: 'Failed to update the blog.' }
      console.error('Error updating blog:', response.statusText)
    }
  } catch (error) {
    formError.value = { general: 'An unexpected error occurred. Please try again.' }
    console.error('Request failed:', error)
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <form @submit.prevent="updateBlog" class="mx-auto" enctype="multipart/form-data">
            <div class="blog-input mb-5">
              <label for="title" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Blog title</label>
              <input
                v-model="blog.title"
                type="text"
                id="title"
                class="block w-full rounded-lg border bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                placeholder=""
              />
              <div v-if="formError.title" class="mt-1 text-xs text-red-500">{{ formError.title }}</div>
            </div>

            <div class="blog-textarea mb-5">
              <label for="textarea" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Your
                message</label>
              <textarea
                v-model="blog.context"
                id="textarea"
                rows="4"
                class="block w-full rounded-lg border bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                placeholder="Write your thoughts here..."
              ></textarea>
              <div v-if="formError.context" class="mt-1 text-xs text-red-500">{{ formError.context }}</div>
            </div>

            <div class="blog-fileinput flex w-full items-center justify-center gap-7">
              <img width="200" :src="getImage(oldImage)" alt="Blog Image" class="rounded-lg shadow-lg" />
              <label
                for="dropzone-file"
                class="flex h-64 w-full cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed bg-gray-50 hover:bg-gray-100 dark:bg-gray-700"
              >
                <div class="flex flex-col items-center justify-center pb-6 pt-5">
                  <svg class="mb-4 h-8 w-8 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg"
                       fill="none" viewBox="0 0 20 16">
                    <path
                      stroke="currentColor"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"
                    />
                  </svg>
                  <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span
                    class="font-semibold">Click to upload</span> or drag and drop</p>
                  <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                </div>
                <input
                  @change="(e) => (blog.image = e.target.files[0])"
                  id="dropzone-file"
                  type="file"
                  class="hidden"
                />
              </label>
              <div v-if="formError.image" class="mt-1 text-xs text-red-500">{{ formError.image }}</div>
            </div>

            <button
              type="submit"
              :disabled="isLoading"
              class="mt-4 w-full rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-800 sm:w-auto"
            >
              {{ isLoading ? 'Updating...' : 'Submit' }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

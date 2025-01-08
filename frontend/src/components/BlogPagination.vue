<script setup>
const props = defineProps({
  blogs: Object, // Assuming blogs is an object as per the structure
});

const emit = defineEmits(['updateBlogs']); // Emit event to fetch new blogs

const goToPage = (url) => {
  if (url) {
    // Emit the URL to the parent component to trigger data fetching
    emit('updateBlogs', url);
  }
};
</script>

<template>
  <!-- Pagination controls -->
  <div class="mt-4 flex justify-center">
    <!-- Previous button -->
    <button
      v-if="blogs.prev_page_url"
      @click="goToPage(blogs.prev_page_url)"
      class="bg-primary-700 hover:bg-primary-800 rounded-lg px-4 py-2 text-white"
    >
      Previous
    </button>

    <!-- Page number buttons -->
    <div class="flex space-x-2">
      <button
        v-for="link in blogs.links"
        :key="link.label"
        @click="goToPage(link.url)"
        :class="{
          'bg-primary-700 hover:bg-primary-800 text-white': link.active,
          'bg-gray-300 text-gray-600': !link.active,
        }"
        class="rounded-lg px-4 py-2"
        :disabled="!link.url"
      >
        <span v-html="link.label"></span>
      </button>
    </div>

    <!-- Next button -->
    <button
      v-if="blogs.next_page_url"
      @click="goToPage(blogs.next_page_url)"
      class="bg-primary-700 hover:bg-primary-800 ml-4 rounded-lg px-4 py-2 text-white"
    >
      Next
    </button>
  </div>
</template>

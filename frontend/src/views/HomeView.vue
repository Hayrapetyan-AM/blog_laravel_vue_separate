<script setup>
import { ref, onMounted } from 'vue';
import BlogSmall from '@/components/Blog/BlogSmall.vue';
import WelcomeHero from '@/components/WelcomeHero.vue';
import BlogPagination from '@/components/BlogPagination.vue';

const blogs = ref([]);
const blogData = ref({});

// Function to fetch blogs after CSRF token is set
const fetchBlogs = async (url) => {
  try {
    const response = await fetch(url, {
      method: 'GET',
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`, // Use the stored token
        'Content-Type': 'application/json',
      },
      credentials: 'include', // Include credentials for Sanctum
    });

    if (response.ok) {
      const data = await response.json();
      blogs.value = data.data; // Assuming `data.data` contains the posts
      blogData.value = data;

    } else {
      console.error('Error fetching blogs:', response.statusText);
    }
  } catch (error) {
    console.error('Request failed:', error);
  }
};

// Initial data fetch on mounted
onMounted(() => {
  fetchBlogs(`${import.meta.env.VITE_BASE_URL}/all-blogs?page=1`);
});

// Listener for pagination change
const updateBlogs = (url) => {
  fetchBlogs(url);
};
</script>

<template>
    <main v-for="blog in blogs" :key="blog.id">
      <BlogSmall :blog="blog" :isAuthor="false" />
    </main>
    <BlogPagination :blogs="blogData" @updateBlogs="updateBlogs" />
</template>

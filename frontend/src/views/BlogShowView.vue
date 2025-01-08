<script setup>
import { ref, onMounted } from 'vue';
import BlogSmall from '@/components/Blog/BlogSmall.vue';
import WelcomeHero from '@/components/WelcomeHero.vue';
import BlogPagination from '@/components/BlogPagination.vue';

const blogs = ref([]);
const blogData = ref({});
const user = ref({});
let showHero = ref(false);

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
      blogs.value = data.blogs.data; // Assuming `data.data` contains the posts
      blogData.value = data.blogs;
      user.value = data.user;
    } else {
      console.error('Error fetching blogs:', response.statusText);
    }
  } catch (error) {
    console.error('Request failed:', error);
  }
};

// Initial data fetch on mounted
onMounted(() => {
  if (localStorage.getItem('token')) {
    fetchBlogs(`${import.meta.env.VITE_BASE_URL}/blogs?page=1`);
  } else {
    showHero.value = true;
  }
});

// Listener for pagination change
const updateBlogs = (url) => {
  fetchBlogs(url);
};
</script>

<template>
  <div v-if="!showHero">
    <main v-for="blog in blogs" :key="blog.id">
      <BlogSmall :blog="blog" :isAuthor="user.id === blog.user_id" />
    </main>
    <BlogPagination :blogs="blogData" @updateBlogs="updateBlogs" />
  </div>

  <WelcomeHero v-if="showHero"></WelcomeHero>
</template>

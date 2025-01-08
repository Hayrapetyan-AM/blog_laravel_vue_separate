<script setup>
import { useRoute } from 'vue-router';
import { onMounted, ref } from "vue";
import BlogShow from "@/components/Blog/BlogShow.vue";

const route = useRoute();
const blog = ref({});
const user = ref({});
const fetchBlog = async (url) => {
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
      blog.value = data;
      user.value = data.user;
    } else {
      console.error('Error fetching blogs:', response.statusText);
    }
  } catch (error) {
    console.error('Request failed:', error);
  }
};

onMounted(() => {
  fetchBlog(`/api/view-blog/${route.params.id}`);
});

</script>

<template>
  <BlogShow :blog="blog" :user="user"></BlogShow>
</template>

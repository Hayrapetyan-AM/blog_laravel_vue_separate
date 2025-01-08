<script setup>
import { RouterLink } from 'vue-router'
import { onMounted, ref } from "vue";

const hasToken = ref('');
const logout = () => {
  localStorage.removeItem('token')
  hasToken.value = '';
  window.location.href = '/';
}
onMounted(() => {
  hasToken.value = localStorage.getItem('token');
})
</script>

<template>
  <div class="main">
    <header>
      <div class="nav-wrapper">
        <nav class="flex gap-5">
          <RouterLink to="/">Home</RouterLink>
          <div class="flex gap-3" v-if="!hasToken">
            <RouterLink to="/login">Login</RouterLink>
            <RouterLink to="/register">Register</RouterLink>
          </div>
          <div v-if="hasToken" class="flex gap-5" >
            <RouterLink @click="logout" to="/logout">Logout</RouterLink>
            <RouterLink  to="/blog/create">Create blog</RouterLink>
            <RouterLink  to="/blog/show">Your blogs</RouterLink>
          </div>
        </nav>
      </div>
    </header>
    <div class="m-10">
      <RouterView></RouterView>
    </div>
  </div>
</template>

<style scoped>
.main {
  padding: 3%;
  font-size: 1.6rem;
}

.nav-wrapper{
  display: flex;
  justify-content: end;
}
</style>

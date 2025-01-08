<script setup>

const props = defineProps({
  blog: Object,
  user: Object,
});

console.log(props.blog)
const getImage = (imagePath) => {
  return import.meta.env.VITE_BASE_PATH + '/' +  imagePath;
}
const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: 'numeric',
    minute: 'numeric',
    second: 'numeric',
  });
};
</script>

<template>
  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <main
        class="bg-white pb-16 pt-8 text-white antialiased lg:pb-24 lg:pt-16 dark:bg-gray-900"
      >
        <div
          class="mx-auto flex max-w-screen-xl justify-between px-4"
        >
          <article
            class="format format-sm sm:format-base lg:format-lg format-blue dark:format-invert mx-auto w-full max-w-2xl"
          >
            <header class="not-format mb-4 lg:mb-6">
              <address
                class="mb-6 flex items-center not-italic"
              >
                <div
                  class="mr-3 inline-flex items-center text-sm text-gray-900 dark:text-white"
                >
                  <div>
                    <a
                      href="#"
                      rel="author"
                      class="text-xl font-bold text-gray-900 dark:text-white"
                    >{{ user.name }}</a
                    >
                    <p
                      class="text-base text-gray-500 dark:text-gray-400"
                    >
                      <time
                        pubdate
                        datetime="2022-02-08"
                        title="February 8th, 2022"
                      >
                        {{
                          formatDate(
                            blog.created_at,
                          )
                        }}
                      </time>
                    </p>
                  </div>
                </div>
              </address>
            </header>

            <figure>
              <img
                :src="getImage(blog.image)"
                alt=""
                class="mb-12 rounded-lg"
              />
            </figure>

            <h1 class="mb-2 text-5xl">{{ blog.title }}</h1>
            <p>{{ blog.context }}</p>
          </article>
        </div>
      </main>
    </div>
  </div>
</template>

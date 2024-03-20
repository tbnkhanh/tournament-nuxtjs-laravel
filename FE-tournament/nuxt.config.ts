export default defineNuxtConfig({
  css: ["~/assets/main.scss"],
  modules: [
    '@nuxtjs/tailwindcss',
    '@pinia/nuxt',
  ],
  postcss: {
    plugins: {
      tailwindcss: {},
      autoprefixer: {},
    },
  },
  devtools: { enabled: true },
  // imports: {
  //   dirs: ['types/*.ts', 'store/*.ts', 'types/**/*.ts'],
  // },
})

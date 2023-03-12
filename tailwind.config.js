/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./assets/**/*.{vue,ts}", './templates/**/*.twig'],
  theme: {
    extend: {},
  },
  plugins: [
      require('@tailwindcss/forms')
  ],
}

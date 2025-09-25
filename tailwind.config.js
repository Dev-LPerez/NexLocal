/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './app/**/*.php',
  ],
  theme: {
    extend: {
      colors: {
        'primary-dark': '#49225B',
        'primary': '#6E3482',
        'secondary': '#A56ABD',
        'light': '#E7DBEF',
        'background': '#F5EBFA',
      },
    },
  },
  plugins: [],
}
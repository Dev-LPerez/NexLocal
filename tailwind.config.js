import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    // AÑADIMOS LA ESTRATEGIA PARA EL MODO OSCURO
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            // HE VUELTO A AÑADIR TU PALETA DE COLORES PERSONALIZADA
            colors: {
                'primary-dark': '#49225B',
                'primary': '#6E3482',
                'secondary': '#A56ABD',
                'light': '#E7DBEF',
                'background': '#F5EBFA',
            },
        },
    },

    plugins: [forms],
};
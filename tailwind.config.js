import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
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
            colors: {
                // Nueva paleta de colores del diseño
                primary: '#8b5cf6', // Morado vibrante
                accent: '#c084fc',  // Lila claro
                secondary: '#e9d5ff', // Lavanda
                background: '#fdfcff', // Blanco perla
            },
            borderRadius: {
                // Radio de borde personalizado
                'custom': '0.75rem',
            },
            boxShadow: {
                // Sombra personalizada para efectos
                'primary': '0 10px 15px -3px rgba(139, 92, 246, 0.2), 0 4px 6px -4px rgba(139, 92, 246, 0.2)',
                'primary-lg': '0 20px 25px -5px rgba(139, 92, 246, 0.25), 0 8px 10px -6px rgba(139, 92, 246, 0.25)',
            }
        },
    },

    plugins: [forms],
};
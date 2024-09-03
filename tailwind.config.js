import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
        　colors: {
        'hawaiian-blue': '#4BB4E6',
        'hawaiian-green': '#50C878',
        'hawaiian-pink': '#FF69B4',
        'hawaiian-yellow': '#FFD700',
        },
        fontFamily: {
        'pacifico': ['Pacifico', 'cursive'],
        },
        fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};

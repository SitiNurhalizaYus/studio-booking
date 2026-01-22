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
            fontFamily: {
                sans: ['Poppins', 'ui-sans-serif', 'system-ui'],
            },
            colors: {
                taupe: '#C9B8A6',       // sidebar
                cream: '#F5F0EB',       // background
                cocoa: '#B9A390',       // primary button
                espresso: '#3A2F2A',    // heading
                sage: '#A3B18A',        // confirmed
                amberSoft: '#E6C9A8',   // pending
                terracotta: '#C97C5D',  // cancelled
            }
        },
    },

    plugins: [forms],
};

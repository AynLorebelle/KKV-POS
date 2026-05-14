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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#FDE047', // Bright sunlit yellow (Tailwind yellow-300)
                secondary: '#FFFFFF', // Clean white
                accent: '#000000', // Bold black
                wood: {
                    light: '#F5EBE1',
                    DEFAULT: '#E6D3B3',
                    dark: '#D4B895',
                }
            }
        },
    },

    plugins: [forms],
};

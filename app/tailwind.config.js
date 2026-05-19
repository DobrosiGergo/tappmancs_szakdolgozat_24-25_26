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
                dark: {
                    DEFAULT: 'rgb(var(--dark) / <alpha-value>)',
                    mid:     'rgb(var(--dark-mid) / <alpha-value>)',
                    soft:    'rgb(var(--dark-soft) / <alpha-value>)',
                },
                light: {
                    DEFAULT: 'rgb(var(--light) / <alpha-value>)',
                    off:     'rgb(var(--light-off) / <alpha-value>)',
                    dim:     'rgb(var(--light-dim) / <alpha-value>)',
                    border:  'rgb(var(--light-border) / <alpha-value>)',
                },
                muted: {
                    DEFAULT: 'rgb(var(--muted) / <alpha-value>)',
                    light:   'rgb(var(--muted-light) / <alpha-value>)',
                    dark:    'rgb(var(--muted-dark) / <alpha-value>)',
                },
            },
        },
    },

    plugins: [forms],
};

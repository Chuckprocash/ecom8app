import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './node_modules/flowbite/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                // Brand colors (blue)
                brand: {
                strong: '#0e7490',   // your desired hover color
                medium: '#06b6d4',   // for focus:ring-brand-medium
                softer: '#eff6ff',
                soft: '#dbeafe',
                subtle: '#bfdbfe',
                DEFAULT: '#3b82f6',
                strong: '#1e40af',
                },
                // Success colors (green)
                success: {
                softer: '#f0fdf4',
                soft: '#dcfce7',
                subtle: '#bbf7d0',
                DEFAULT: '#22c55e',
                strong: '#15803d',
                },
                // Error/Danger colors (red)
                error: {
                softer: '#fef2f2',
                soft: '#fee2e2',
                subtle: '#fecaca',
                DEFAULT: '#ef4444',
                strong: '#b91c1c',
                },
                // Warning colors (yellow/amber)
                warning: {
                softer: '#fffbeb',
                soft: '#fef3c7',
                subtle: '#fde68a',
                DEFAULT: '#f59e0b',
                strong: '#b45309',
                },
                // Info colors (cyan)
                info: {
                softer: '#ecfeff',
                soft: '#cffafe',
                subtle: '#a5f3fc',
                DEFAULT: '#06b6d4',
                strong: '#0e7490',
                },
                // Foreground colors (for text)
                fg: {
                'brand-strong': '#1e40af',
                'success-strong': '#15803d',
                'error-strong': '#b91c1c',
                'warning-strong': '#b45309',
                'info-strong': '#0e7490',
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        }
    },

    plugins: [
        forms,
        require('flowbite/plugin'),
    ],
};

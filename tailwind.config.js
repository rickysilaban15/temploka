/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#EEFDFC',
                    100: '#D1FAF7', 
                    200: '#A8F5F0',
                    300: '#6EE9E3',
                    400: '#2DD4CD',
                    500: '#009689',
                    600: '#007B70',
                    700: '#00635B',
                    800: '#004F49',
                    900: '#00413C',
                },
            },
            fontFamily: {
                sans: ['Inter', 'system-ui', 'sans-serif'],
            },
            borderRadius: {
                'button': '20px',
            },
            boxShadow: {
                'soft': '0px 4px 15px rgba(0, 0, 0, 0.1)',
                'medium': '0px 8px 25px rgba(0, 0, 0, 0.15)',
            }
        },
    },
    plugins: [],
}
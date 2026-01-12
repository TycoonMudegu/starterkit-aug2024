/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/views/**/*.php"
  ],
  darkMode: 'class', // Enable dark mode using a class
  theme: {
    extend: {
      colors: {
        'primary-green': '#52ff3f',
        'primary-dark-green': '#006400',
        'primary-blue': '#0033cc',
        'primary-dark-blue': '#001a66',
        'primary-blue2': '#bddbe8',
        'primary-dark-blue2': '#5a7c85',
        'primary-green2': '#99cc66',
        'primary-dark-green2': '#4d6633',
        'primary-white': '#FFFFFF',
        'primary-dark-white': '#bfbfbf',
        'primary-gray': '#E6F0DC',
        'primary-dark-gray': '#99a386',
      },
      fontFamily: {
        montserrat: ['Montserrat', 'sans-serif'],
        seaweed: ['Seaweed Script', 'cursive'],
        playfair: ['Playfair Display', 'serif'],
        glitch: ['"Press Start 2P"', 'cursive'], // Add a glitchy font like Press Start 2P
      },
      keyframes: {
        glitch: {
          '0%': { transform: 'translate(0)' },
          '20%': { transform: 'translate(-2px, -2px)' },
          '40%': { transform: 'translate(2px, 2px)' },
          '60%': { transform: 'translate(-1px, 1px)' },
          '80%': { transform: 'translate(1px, -1px)' },
          '100%': { transform: 'translate(0)' },
        },
      },
      animation: {
        glitch: 'glitch 1s infinite',
      },
    },
  },
  plugins: [],
}
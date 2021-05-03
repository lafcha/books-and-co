const colors = require('tailwindcss/colors')

module.exports = {
  theme: {
    fontFamily: {
      sans: ['Raleway', 'sans-serif'],
      cursive :['"Lily Script One"', 'cursive'],
    },
    extend: {

    }
  },
  variants: {
    extend: {
      borderColor: ['focus-visible'],
      opacity: ['disabled'],
    }
  }
}
const { yellow } = require('tailwindcss/colors')
const colors = require('tailwindcss/colors')

module.exports = {
  theme: {
    fontFamily: {
      sans: ['Raleway', 'sans-serif'],
      cursive :['"Lily Script One"', 'cursive'],
    },
    extend: {

    },
    colors: {
      blue: {
        dark: '#383940',
        DEFAULT:'#363C54',
      },
      pink:{
        DEFAULT: '#DB1D7D',
        light: '#FCEDF5'
      },
      yellow:{
        light:'#FFFAE4',
        DEFAULT: '#FFD524',
        dark: '#F2B111'
      },
      green: {
        DEFAULT:'#26C6BA',
        light: '#DDFAF6'
      },
      grey:{
        DEFAULT: '#EDEDED',
        dark: '#8C8C8C'
      },
      white:{
        DEFAULT:'#FFFFFF'
      }
    },
    letterSpacing: {
      tightest: '-.075em',
      tighter: '-.05em',
      tight: '-.025em',
      normal: '0',
      wide: '.025em',
      wider: '.05em',
      widest: '.5em',
     }
  },
  variants: {
    extend: {
      borderColor: ['focus-visible'],
      opacity: ['disabled'],
    }
  },
  minWidth: {
    '0': '0',
    '1/4': '25%',
    '1/2': '50%',
    '3/4': '75%',
    'full': '100%',
  },
  plugins: [],
}
const colors = require('tailwindcss/colors')

module.exports = {
  purge: [],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {},
    colors: {
      transparent: 'transparent',
      current: 'currentColor',
      black: colors.black,
      white: colors.white,
      gray: colors.blueGray,
      indigo: colors.indigo,
      red: colors.red,
      yellow: colors.amber,
      orange: colors.orange,
      green: colors.emerald,
      teal: colors.teal,
      blue: colors.blue,
      violet: colors.violet,
      rose: colors.rose
    }
  },
  variants: {
    extend: {},
  },
  plugins: [],
}

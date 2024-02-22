/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
    "./node_modules/flowbite/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        'dark-alt': '#321370',
        'light-gray': '#E7E5EA',
        'blue': '#4F77FF',
        'purple': '#886CFF',
        "base": "#FFFFFF",
        "primary": "#886CFF",
        "secondary": "#61DCDF",
      },
      backgroundImage: {
        'purple-gradient': 'linear-gradient(185.78deg, #61DCDF 0.38%, #886CFF 100%)',
        'orange-gradient': 'linear-gradient(92.57deg, #FFB95B 1.46%, #DA8412 99.26%)',
        'text-gradient': 'linear-gradient(90deg, #FFC0EC 0.38%, #857FFF 100%)'
      },
      backgroundColor: {
        'dark': '#12022F',
        'alt': '#321370',
        'light': '#F8FAFC',
        'light-alt': '#F7F5F1',
        'white': '#FFFFFF',
      }
    }
  },
}

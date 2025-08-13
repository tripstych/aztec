/** @type {import('tailwindcss').Config} */
module.exports = {
  purge: ["./src/**/*.{js,jsx,ts,tsx}", "./public/index.html"],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {
      fontFamily: {
        poppins: ["Poppins", "sans-serif"],
        montserrat: ["Montserrat", "san-serif"],
        oswald: ["Oswald", "san-serif"],
        proximanova: ["Proxima Nova", "san-serif"],
        merriweather: ["Merriweather", "san-serif"],
      },
      colors: {
        cBlue: {
          DEFAULT: "#1194e4", // Default shade for customBlue
          light: "#33aaff", // Lighter shade
          dark: "#0b74c9", // Darker shade
        },
        cGreen: {
          DEFAULT: "#39b972",
        },
        cBlack: {
          DEFAULT: "#1d2124",
        },
      },
    },
    screens: {
      sm: "640px",
      // => @media (min-width: 640px) { ... }

      md: "768px",
      // => @media (min-width: 768px) { ... }

      lg: "1024px",
      // => @media (min-width: 1024px) { ... }

      xl: "1280px",
      // => @media (min-width: 1280px) { ... }

      "2xl": "1536px",
      // => @media (min-width: 1536px) { ... }
    },
  },
  variants: {
    extend: {},
  },
  plugins: [],
};

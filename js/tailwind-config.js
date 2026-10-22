window.tailwind = window.tailwind || {};
window.tailwind.config = {
  theme: {
    extend: {
      colors: {
        pine: {
          950: "#0E2419",
          900: "#143026",
          800: "#1B4332",
          700: "#245C42",
        },
        cream: {
          DEFAULT: "#F7F3EB",
          deep: "#EFE6D6",
        },
        paper: "#FFFCF8",
        clay: {
          DEFAULT: "#9A3B12",
          bright: "#E07A3D",
        },
        ink: "#1C2B24",
        mist: "#3E5348",
        wa: "#146C43",
      },
      fontFamily: {
        display: ["Fraunces", "Georgia", "serif"],
        sans: ["Outfit", "Segoe UI", "sans-serif"],
      },
      boxShadow: {
        card: "0 16px 40px -28px rgba(20, 48, 38, 0.55)",
      },
    },
  },
};

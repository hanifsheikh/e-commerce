module.exports = {
    purge: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    darkMode: false, // or 'media' or 'class'
    theme: {
        extend: {
            spacing: {
                slider: "27rem",
                "slider-lg": "17rem",
                "slider-md": "17rem",
            },
            fontSize: {
                xxs: ["11px", "10px"],
                base: ["14px", "24px"],
                lg: ["16px", "28px"],
                xl: ["20px", "32px"],
            },
            fontFamily: {
                sans: ["roboto"],
            },
            colors: {
                navy: "#080A2B",
                dark: "#111723",
                deepest: "#0D111B",
                light: "#E8EDF7",
                white: "#FFFFFF",
                blue: {
                    700: "#1D4ED8",
                    DEFAULT: "#027BFF",
                    fb: "#3b5499",
                },
                "dark-gray": "#304055",
                gray: {
                    200: "#E5E7EB",
                    600: "#4B5563",
                    DEFAULT: "#969AB2",
                },
                purple: "#8862E0",
                green: {
                    500: "#10B981",
                    DEFAULT: "#1DD795",
                    600: "#059669",
                },
                "light-blue": "#2196F3",
                yellow: "#FFAF01",
                red: {
                    600: "#DC2626",
                    DEFAULT: "#FA424A",
                },
                "light-gray": {
                    100: "#F5F5F5",
                    DEFAULT: "#E9E9E9",
                },
                paste: {
                    DEFAULT: "#28dea6",
                    800: "#188B84",
                },
                orange: {
                    100: "#FFA400",
                    DEFAULT: "#FD6506",
                    700: "#FF4500",
                },
            },
            boxShadow: {
                sm: "0 1px 2px 0 rgba(0, 0, 0, 0.05)",
                DEFAULT:
                    "0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)",
                md: "0 0 8px 0 rgba(0, 0, 0, 0.15)",
                lg: "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)",
                xl: "0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)",
                "2xl": "0 8px 50px 0 rgba(0, 0, 0, 0.08)",
                "3xl": "0 35px 60px -15px rgba(0, 0, 0, 0.3)",
                inner: "inset 0 2px 4px 0 rgba(0, 0, 0, 0.06)",
                none: "none",
            },
        },
    },
    variants: {
        extend: {},
    },
    plugins: [function ({ addComponents }) {
        addComponents({
          '.container': {
            maxWidth: '100%',
            '@screen sm': {
              maxWidth: '640px',
            },
            '@screen md': {
              maxWidth: '768px',
            },
            '@screen lg': {
              maxWidth: '1280px',
            },
            '@screen xl': {
              maxWidth: '100%',
            },
          }
        })
      }],
};

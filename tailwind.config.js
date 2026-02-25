/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './app/Livewire/**/*.php',
  ],
  theme: {
    extend: {
      fontFamily: {
        serif: ['"Playfair Display"', 'Cormorant Garamond', 'Georgia', 'serif'],
        sans: ['Inter', 'Montserrat', 'system-ui', 'sans-serif'],
      },
      colors: {
        // Couleurs Mathey-Tissot
        'mathey-white': '#FFFFFF',
        'mathey-cream': '#F8F6F4',
        'mathey-gold': '#C5A059',
        'mathey-blue': '#1A2B3C',
        'mathey-text': '#2C2C2C',
        'mathey-gray': '#6B6B6B',
        'mathey-red': '#B22222',
        'mathey-border': '#E0E0E0',
        
        // Alias pour compatibilité
        swiss: {
          red: '#B22222',
          gold: '#C5A059',
          blue: '#1A2B3C',
        },
      },
      fontSize: {
        'hero': '3.5rem',
        'title': '3rem',
        'subtitle': '2rem',
      },
      spacing: {
        '18': '4.5rem',
        '88': '22rem',
      },
      animation: {
        'fade-in': 'fadeIn 0.6s ease-in-out',
        'slide-up': 'slideUp 0.6s ease-out',
        'zoom-in': 'zoomIn 0.3s ease-out',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { opacity: '0', transform: 'translateY(30px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        zoomIn: {
          '0%': { transform: 'scale(1)' },
          '100%': { transform: 'scale(1.05)' },
        },
      },
      transitionProperty: {
        'height': 'height',
        'spacing': 'margin, padding',
      },
    },
  },
  plugins: [],
}

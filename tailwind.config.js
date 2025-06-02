import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './vendor/laravel/jetstream/**/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js', // Jika Anda menggunakan JS untuk memanipulasi kelas Tailwind
    './resources/js/**/*.vue', // Jika Anda menggunakan Vue dengan kelas Tailwind
  ],

  // ⬇️ Hapus atau komentari safelist ini untuk pengembangan
  // safelist: [
  //   'peer-checked:bg-primary',
  //   'peer-checked:border-primary',
  //   'peer-checked:text-white', // Ini juga salah karena primary Anda terang
  // ],
  // Sebaiknya safelist hanya digunakan jika benar-benar diperlukan untuk kelas dinamis
  // yang tidak bisa dideteksi oleh Tailwind JIT. Untuk peer-checked, ini tidak perlu.

  theme: {
    extend: {
      colors: {
        primary: '#A8BB21', // Warna primer Anda
        // Anda bisa menambahkan shades jika diperlukan, misal:
        // primary: {
        //   DEFAULT: '#A8BB21',
        //   '50': '#f7f9e6',
        //   '100': '#eff2ce',
        //   // ... (gunakan generator palet warna untuk shades lainnya)
        //   '900': '#202506'
        // },
        // Warna teks yang akan kontras dengan primary Anda
        'primary-content': '#202506', // Contoh: warna gelap untuk teks di atas bg-primary
        // Atau gunakan warna slate/gray yang sudah ada:
        // 'primary-content': defaultTheme.colors.slate[800],
      },
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
      },
    },
  },

  plugins: [
    forms,
    typography,
  ],
};
// 📄 พิกัดไฟล์: tailwind.config.js

/** @type {import('tailwindcss').Config} */
export default {
    content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
      "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
      "./storage/framework/views/*.php",
    ],
    theme: {
      extend: {
        // 🌟 ย้ายการตั้งค่าฟอนต์ส่วนกลางมาไว้ตรงนี้ เพื่อให้เรียกใช้คลาส font-sans ได้สมบูรณ์แบบครับ
        fontFamily: {
          sans: ['Prompt', 'Sarabun', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        },
      },
    },
    plugins: [],
  }
import 'bootstrap';
import axios from 'axios';
import Alpine from 'alpinejs';


const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (token) axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
window.Alpine = Alpine;

Alpine.start();
// Persistir tema
const root = document.documentElement;
const saved = localStorage.getItem('theme');
if (saved) root.setAttribute('data-theme', saved);
const isDark = saved === 'dark';
document.getElementById('themeToggle')?.toggleAttribute('checked', isDark);

document.getElementById('themeToggle')?.addEventListener('change', (e) => {
  const v = e.target.checked ? 'dark' : 'light';
  root.setAttribute('data-theme', v);
  localStorage.setItem('theme', v);
});

// Sidebar
document.getElementById('btnSidebar')?.addEventListener('click', () => {
  document.getElementById('sidebar')?.classList.toggle('open');
});

// User dropdown
const userBtn = document.getElementById('userBtn');
const userMenu = document.getElementById('userMenu');
userBtn?.addEventListener('click', () => userMenu?.classList.toggle('d-block'));
document.addEventListener('click', (e) => {
  if (!userBtn?.contains(e.target) && !userMenu?.contains(e.target)) {
    userMenu?.classList.remove('d-block');
  }
});

// Loader helpers (llámalos desde vistas si necesitas)
/* global window */
window.showLoader = () => document.getElementById('loader')?.removeAttribute('hidden');
window.hideLoader = () => document.getElementById('loader')?.setAttribute('hidden','');
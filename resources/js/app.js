import 'bootstrap';
import axios from 'axios';
import Alpine from 'alpinejs';


const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (token) axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
window.Alpine = Alpine;

Alpine.start();

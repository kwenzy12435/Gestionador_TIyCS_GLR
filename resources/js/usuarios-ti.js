// resources/js/usuarios-ti.js
function confirmDelete(form) {
  return confirm('¿Seguro que deseas eliminar este usuario? Esta acción no se puede deshacer.');
}

// Anti brute-force UI delay example (simple UX)
document.addEventListener('submit', (e) => {
  if (e.target.matches('form[method="POST"]')) {
    const btn = e.target.querySelector('button[type="submit"]');
    if (btn) {
      btn.disabled = true;
      setTimeout(() => btn.disabled = false, 2000); // previene spam rápido
    }
  }
});

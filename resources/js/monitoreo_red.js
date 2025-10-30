// Confirmación al eliminar
function confirmDelete(form) {
  return confirm('¿Seguro que deseas eliminar este registro? Esta acción no se puede deshacer.');
}

// Evita spam rápido (UX anti brute-force)
document.addEventListener('submit', (e) => {
  if (e.target.matches('form[method="POST"], form[method="PUT"]')) {
    const btn = e.target.querySelector('button[type="submit"]');
    if (btn) {
      btn.disabled = true;
      setTimeout(() => btn.disabled = false, 2000);
    }
  }
});

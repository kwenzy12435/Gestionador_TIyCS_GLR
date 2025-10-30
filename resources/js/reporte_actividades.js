// resources/js/reporte_actividades.js
function confirmDelete(form) {
  return confirm('¿Seguro que deseas eliminar este reporte? Esta acción no se puede deshacer.');
}

// Evita spam rápido al enviar formularios (anti brute-force UX)
document.addEventListener('submit', (e) => {
  if (e.target.matches('form[method="POST"], form[method="PUT"]')) {
    const btn = e.target.querySelector('button[type="submit"]');
    if (btn) {
      btn.disabled = true;
      setTimeout(() => btn.disabled = false, 2000);
    }
  }
});

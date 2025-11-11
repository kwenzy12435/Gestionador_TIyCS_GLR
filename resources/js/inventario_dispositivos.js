// resources/js/inventario_dispositivos.js

// Confirmación de borrado
function confirmDelete(form) {
  return confirm('¿Seguro que deseas eliminar este dispositivo? Esta acción no se puede deshacer.');
}

// Debounce de búsqueda (UX mejorado)
(function() {
  const form = document.getElementById('filtroInv');
  if (!form) return;

  const search = form.querySelector('input[name="search"]');
  let t;
  if (search) {
    search.addEventListener('input', () => {
      clearTimeout(t);
      t = setTimeout(() => form.requestSubmit(), 500);
    });
  }

  // Anti-spam en submit
  form.addEventListener('submit', () => {
    const btn = form.querySelector('button[type="submit"]');
    if (btn) {
      btn.disabled = true;
      setTimeout(() => (btn.disabled = false), 1500);
    }
  });
})();

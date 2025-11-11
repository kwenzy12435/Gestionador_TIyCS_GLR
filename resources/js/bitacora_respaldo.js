// resources/js/bitacora_respaldo.js

// Confirmación de borrado
function confirmDelete(form) {
  return confirm('¿Seguro que deseas eliminar este registro? Esta acción no se puede deshacer.');
}

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('filtroBitacora');
  if (form) {
    // Debounce de búsqueda
    const input = form.querySelector('input[name="search"]');
    let t;
    input?.addEventListener('input', () => {
      clearTimeout(t);
      t = setTimeout(() => form.requestSubmit(), 500);
    });

    // Anti-spam submit
    form.addEventListener('submit', () => {
      const btn = form.querySelector('button[type="submit"]');
      if (btn) {
        btn.disabled = true;
        setTimeout(() => (btn.disabled = false), 1500);
      }
    });

    // Atajos que insertan texto en búsqueda
    form.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-quick]');
      if (!btn) return;
      const q = btn.getAttribute('data-quick');
      if (input) {
        input.value = q;
        form.requestSubmit();
      }
    });
  }
});

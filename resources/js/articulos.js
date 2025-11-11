// resources/js/articulos.js

// Confirmación de borrado
function confirmDelete(form) {
  return confirm('¿Seguro que deseas eliminar este artículo? Esta acción no se puede deshacer.');
}

document.addEventListener('DOMContentLoaded', () => {
  // Debounce de búsqueda en index
  const filtro = document.getElementById('filtroArticulos');
  if (filtro) {
    const input = filtro.querySelector('input[name="search"]');
    let t;
    input?.addEventListener('input', () => {
      clearTimeout(t);
      t = setTimeout(() => filtro.requestSubmit(), 500);
    });
    filtro.addEventListener('submit', () => {
      const btn = filtro.querySelector('button[type="submit"]');
      if (btn) {
        btn.disabled = true;
        setTimeout(() => (btn.disabled = false), 1500);
      }
    });
  }

  // Filtrado de subcategorías según categoría (create/edit)
  const form = document.getElementById('formArticulo');
  if (form) {
    const catSel = form.querySelector('#categoria');
    const subSel = form.querySelector('#subcategoria');

    const filterSubs = () => {
      if (!catSel || !subSel) return;
      const cat = catSel.value;
      const current = subSel.value;
      let hasMatch = false;

      [...subSel.options].forEach(opt => {
        if (!opt.value) return; // "— Ninguna —"
        const ok = opt.getAttribute('data-categoria') === cat;
        opt.hidden = !ok;
        if (ok) hasMatch = true;
      });

      // Si la subcategoría actual no corresponde, limpia
      const currentOpt = subSel.selectedOptions[0];
      if (currentOpt && currentOpt.hidden) subSel.value = '';

      // Si no hay coincidencias visibles, deja "Ninguna"
      if (!hasMatch) subSel.value = '';
    };

    catSel?.addEventListener('change', filterSubs);
    filterSubs(); // inicial
  }
});

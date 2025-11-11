// resources/js/colaboradores.js

// Anti-spam de submits y búsqueda con debounce
(function () {
  const form = document.getElementById('filtroColabs');
  if (form) {
    let t;
    const input = form.querySelector('input[name="search"]');
    input?.addEventListener('input', () => {
      clearTimeout(t);
      t = setTimeout(() => form.requestSubmit(), 500);
    });

    form.addEventListener('submit', () => {
      const btn = form.querySelector('button[type="submit"]');
      if (btn) {
        btn.disabled = true;
        setTimeout(() => (btn.disabled = false), 1500);
      }
    });
  }
})();

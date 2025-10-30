// resources/js/admin_bajas.js

// Presets rápidos para el rango de fechas
(function() {
  const form = document.getElementById('filtroBajas');
  if (!form) return;

  const desde = form.querySelector('input[name="fecha_desde"]');
  const hasta = form.querySelector('input[name="fecha_hasta"]');

  function format(d) {
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${y}-${m}-${day}`;
  }

  function setRange(days) {
    const end = new Date();
    const start = new Date();
    start.setDate(end.getDate() - (days - 1));
    if (desde) desde.value = format(start);
    if (hasta) hasta.value = format(end);
    form.submit();
  }

  form.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-preset]');
    if (!btn) return;
    const p = btn.getAttribute('data-preset');
    if (p === 'hoy') setRange(1);
    if (p === '7d') setRange(7);
    if (p === '30d') setRange(30);
  });

  // Anti-spam en botones submit
  form.addEventListener('submit', () => {
    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn) {
      submitBtn.disabled = true;
      setTimeout(() => (submitBtn.disabled = false), 2000);
    }
  });
})();

// resources/js/admin_configsistem.js

function confirmDelete(form) {
  return confirm('¿Seguro que deseas eliminar este registro? Esta acción no se puede deshacer.');
}

document.addEventListener('DOMContentLoaded', () => {
  const modalEl   = document.getElementById('registroModal');
  const modal     = modalEl ? new bootstrap.Modal(modalEl) : null;
  const form      = document.getElementById('formRegistro');
  const titleEl   = document.getElementById('modalTitle');
  const nombreEl  = document.getElementById('nombre');
  const catEl     = document.getElementById('categoriaId'); // solo existe en subcategorias
  const errorBox  = document.getElementById('modalError');
  const submitBtn = document.getElementById('modalSubmit');

  if (!modalEl || !form) return;

  const currentTabla = window.location.pathname.split('/').pop() || 'departamentos'; 
  const baseUrl = `/admin/configsistem/${currentTabla}`;

  // Modo crear
  document.querySelectorAll('[data-action="create"]').forEach(btn => {
    btn.addEventListener('click', () => {
      form.action = baseUrl;
      form.method = 'POST';
      form.querySelector('input[name="_method"]')?.remove();
      form.reset();
      errorBox.classList.add('d-none');
      titleEl.textContent = 'Nuevo registro';
      submitBtn.disabled = false;
      modal.show();
    });
  });

  // Modo editar
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-action="edit"]');
    if (!btn) return;

    const id     = btn.getAttribute('data-id');
    const nombre = btn.getAttribute('data-nombre');
    const catId  = btn.getAttribute('data-categoria_id');

    form.action = `${baseUrl}/${id}`;
    form.method = 'POST';

    // Spoofing PUT
    let method = form.querySelector('input[name="_method"]');
    if (!method) {
      method = document.createElement('input');
      method.type = 'hidden';
      method.name = '_method';
      form.appendChild(method);
    }
    method.value = 'PUT';

    nombreEl.value = nombre || '';
    if (catEl && catId) catEl.value = catId;

    errorBox.classList.add('d-none');
    titleEl.textContent = 'Editar registro';
    submitBtn.disabled = false;
    modal.show();
  });

  // Anti spam submit
  form.addEventListener('submit', () => {
    submitBtn.disabled = true;
    setTimeout(() => (submitBtn.disabled = false), 1500);
  });
});

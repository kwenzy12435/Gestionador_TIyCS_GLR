// resources/js/licencias.js

// Utilidad: obtener token CSRF desde un input hidden del form o inyectado por blade
function getCsrf(formEl) {
  const input = formEl?.querySelector('input[name="_token"]');
  if (input) return input.value;
  // fallback: intenta desde cualquier input csrf en el documento
  const any = document.querySelector('input[name="_token"]');
  return any ? any.value : '';
}

// Confirmar eliminación simple
function confirmDelete(form) {
  return confirm('¿Seguro que deseas eliminar este registro? Esta acción no se puede deshacer.');
}

document.addEventListener('DOMContentLoaded', () => {
  const revealModalEl = document.getElementById('revealModal');
  if (revealModalEl) {
    const modal = new bootstrap.Modal(revealModalEl);
    const form = document.getElementById('revealForm');
    const idInput = document.getElementById('licenciaId');
    const resultBox = document.getElementById('revealResult');
    const pwdField = document.getElementById('revealedPassword');
    const errorBox = document.getElementById('revealError');
    const submitBtn = document.getElementById('revealSubmit');
    const copyBtn = document.getElementById('copyBtn');

    // Abrir modal al click en "ojo"
    document.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-action="reveal"]');
      if (!btn) return;
      idInput.value = btn.getAttribute('data-id');
      form.reset();
      resultBox.classList.add('d-none');
      errorBox.classList.add('d-none');
      pwdField.value = '';
      submitBtn.disabled = false;
      modal.show();
    });

    // Enviar al backend para validar password de usuario y revelar
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      if (submitBtn.disabled) return;
      submitBtn.disabled = true;

      const licenciaId = idInput.value;
      const password = form.querySelector('input[name="password"]').value;
      const csrf = getCsrf(form);

      try {
        const resp = await fetch(`/licencias/${licenciaId}/revelar`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json'
          },
          body: JSON.stringify({ password })
        });

        const data = await resp.json();

        if (!resp.ok || !data.success) {
          throw new Error(data.message || 'No se pudo revelar la contraseña.');
        }

        // Mostrar contraseña
        pwdField.value = data.contrasena;
        resultBox.classList.remove('d-none');
        errorBox.classList.add('d-none');

      } catch (err) {
        errorBox.textContent = err.message;
        errorBox.classList.remove('d-none');
        resultBox.classList.add('d-none');
      } finally {
        // Anti brute-force: pequeña espera antes de permitir otro intento
        setTimeout(() => { submitBtn.disabled = false; }, 1500);
      }
    });

    // Copiar
    copyBtn?.addEventListener('click', async () => {
      if (!pwdField.value) return;
      try {
        await navigator.clipboard.writeText(pwdField.value);
        copyBtn.innerHTML = '<i class="bi bi-clipboard-check"></i>';
        setTimeout(() => copyBtn.innerHTML = '<i class="bi bi-clipboard"></i>', 1200);
      } catch {}
    });
  }

  // Generar contraseña rápida (create/edit)
  document.querySelectorAll('#genPwdBtn').forEach(btn => {
    btn.addEventListener('click', () => {
      const input = btn.parentElement.querySelector('input[type="password"]');
      if (!input) return;
      // Generador sencillo (puedes endurecerlo si quieres)
      const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$%*?';
      let s = '';
      for (let i = 0; i < 14; i++) s += chars[Math.floor(Math.random() * chars.length)];
      input.value = s;
      input.dispatchEvent(new Event('input'));
    });
  });
});

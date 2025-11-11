document.addEventListener('DOMContentLoaded', () => {
  // Toggle mostrar/ocultar contraseña
  document.querySelectorAll('.btn-eye').forEach(btn => {
    btn.addEventListener('click', () => {
      const targetSelector = btn.getAttribute('data-target');
      const input = document.querySelector(targetSelector);

      if (!input) return;

      const isText = input.type === 'text';
      input.type = isText ? 'password' : 'text';

      const icon = btn.querySelector('i');
      if (icon) {
        icon.classList.toggle('fa-eye', !isText);
        icon.classList.toggle('fa-eye-slash', isText);
      }
    });
  });

  // Auto-ocultar alertas globales
  setTimeout(() => {
    document.querySelectorAll('.alert-auto').forEach(el => {
      el.style.transition = 'opacity .4s ease';
      el.style.opacity = '0';
      setTimeout(() => el.remove(), 400);
    });
  }, 5000);
});

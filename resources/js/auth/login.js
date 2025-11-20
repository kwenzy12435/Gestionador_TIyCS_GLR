document.addEventListener('DOMContentLoaded', function() {
  // Función para toggle de contraseña
  const togglePassword = (button) => {
    const targetId = button.getAttribute('data-target');
    const input = document.querySelector(targetId);
    
    if (!input) return;

    const icon = button.querySelector('i');
    const isPassword = input.type === 'password';
    
    // Cambiar tipo de input
    input.type = isPassword ? 'text' : 'password';
    
    // Cambiar icono
    if (icon) {
      if (isPassword) {
        icon.classList.replace('fa-eye-slash', 'fa-eye');
      } else {
        icon.classList.replace('fa-eye', 'fa-eye-slash');
      }
    }
    
    // Cambiar clase para estilos y accesibilidad
    button.classList.toggle('visible', isPassword);
    button.setAttribute('aria-label', isPassword ? 'Ocultar contraseña' : 'Mostrar contraseña');
    
    // Mantener foco en el input
    input.focus();
  };

  // Configurar botones de ojo
  const setupPasswordToggles = () => {
    document.querySelectorAll('.btn-eye-external').forEach(btn => {
      btn.addEventListener('click', function() {
        togglePassword(this);
      });
      
      // Soporte para teclado
      btn.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          togglePassword(this);
        }
      });
      
      // Inicializar atributos ARIA
      btn.setAttribute('aria-label', 'Mostrar contraseña');
    });
  };

  // Auto-ocultar alertas globales
  const setupAutoHideAlerts = () => {
    setTimeout(() => {
      document.querySelectorAll('.alert-auto').forEach(el => {
        el.style.transition = 'opacity .4s ease';
        el.style.opacity = '0';
        setTimeout(() => {
          if (el.parentNode) {
            el.remove();
          }
        }, 400);
      });
    }, 5000);
  };

  // Loading en botón de login
  const setupLoginButton = () => {
    const loginForm = document.querySelector('form');
    const loginButton = document.getElementById('login-btn');
    
    if (loginForm && loginButton) {
      loginForm.addEventListener('submit', function() {
        // Solo activar loading si el formulario es válido
        if (this.checkValidity()) {
          const btnText = loginButton.querySelector('.btn-text');
          if (btnText) {
            btnText.textContent = 'Iniciando sesión...';
          }
          loginButton.classList.add('btn-loading');
          loginButton.disabled = true;
          
          // Timeout de seguridad
          setTimeout(() => {
            if (loginButton.classList.contains('btn-loading')) {
              resetLoginButton();
            }
          }, 10000);
        }
      });
    }
  };

  // Función para resetear el botón
  const resetLoginButton = () => {
    const loginButton = document.getElementById('login-btn');
    if (loginButton) {
      loginButton.classList.remove('btn-loading');
      loginButton.disabled = false;
      const btnText = loginButton.querySelector('.btn-text');
      if (btnText) {
        btnText.textContent = 'Iniciar sesión';
      }
    }
  };

  // Inicializar todas las funcionalidades
  const initAll = () => {
    setupPasswordToggles();
    setupAutoHideAlerts();
    setupLoginButton();
  };

  // Ejecutar inicialización
  initAll();
});
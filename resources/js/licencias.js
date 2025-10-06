import 'bootstrap/dist/js/bootstrap.bundle.min.js';

// licencias.js - Manejo de autenticación para licencias

class LicenciasManager {
    constructor() {
        this.accionPendiente = null;
        this.idPendiente = null;
        this.passwordVisible = false;
        this.init();
    }

    init() {
        this.bindEvents();
    }

    bindEvents() {
        // Usar delegación de eventos para mejor rendimiento
        document.addEventListener('click', (e) => {
            // Botones de editar
            if (e.target.closest('.btn-editar')) {
                e.preventDefault();
                const btn = e.target.closest('.btn-editar');
                const id = btn.dataset.id;
                this.solicitarPassword('edit', id);
            }

            // Botones de eliminar
            if (e.target.closest('.btn-eliminar')) {
                e.preventDefault();
                const btn = e.target.closest('.btn-eliminar');
                const id = btn.dataset.id;
                this.solicitarPassword('delete', id);
            }

            // Botón de ojo para mostrar contraseña
            if (e.target.closest('#btnTogglePassword')) {
                e.preventDefault();
                this.togglePassword();
            }
        });

        // Botones específicos de la página show
        if (document.getElementById('btnEditar')) {
            document.getElementById('btnEditar').addEventListener('click', (e) => {
                e.preventDefault();
                const id = this.obtenerIdDeUrl();
                this.solicitarPassword('edit', id);
            });
        }

        if (document.getElementById('btnEliminar')) {
            document.getElementById('btnEliminar').addEventListener('click', (e) => {
                e.preventDefault();
                const id = this.obtenerIdDeUrl();
                this.solicitarPassword('delete', id);
            });
        }

        // Evento para el botón confirmar del modal
        if (document.getElementById('confirmPasswordBtn')) {
            document.getElementById('confirmPasswordBtn').addEventListener('click', () => {
                this.confirmarPassword();
            });
        }

        // Evento para Enter en el input de password
        if (document.getElementById('passwordInput')) {
            document.getElementById('passwordInput').addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    this.confirmarPassword();
                }
            });
        }
    }

    solicitarPassword(accion, id = null) {
        this.accionPendiente = accion;
        this.idPendiente = id;
        
        const modal = new bootstrap.Modal(document.getElementById('passwordModal'));
        modal.show();
        
        this.limpiarModal();
        
        // Enfocar input después de que se muestre el modal
        setTimeout(() => {
            const input = document.getElementById('passwordInput');
            if (input) input.focus();
        }, 500);
    }

    limpiarModal() {
        const input = document.getElementById('passwordInput');
        const error = document.getElementById('passwordError');
        
        if (input) input.value = '';
        if (error) {
            error.textContent = '';
            error.style.display = 'none';
        }
    }

    async confirmarPassword() {
    const input = document.getElementById('passwordInput');
    const errorDiv = document.getElementById('passwordError');
    
    if (!input || !errorDiv) return;
    
    const password = input.value.trim();
    
    if (!password) {
        this.mostrarError('Ingrese su contraseña');
        return;
    }

    try {
        // SOLUCIÓN: Usar la ruta correcta
        const response = await fetch('/licencias/confirmar-password', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.getCsrfToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ password: password })
        });

        const data = await response.json();

        if (data.success) {
            this.ejecutarAccion();
        } else {
            this.mostrarError(data.message || 'Contraseña incorrecta');
        }
    } catch (error) {
        console.error('Error:', error);
        this.mostrarError('Error de conexión con el servidor');
    }
    }

    ejecutarAccion() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('passwordModal'));
        if (modal) modal.hide();

        switch (this.accionPendiente) {
            case 'edit':
                this.editarLicencia();
                break;
            case 'delete':
                this.eliminarLicencia();
                break;
            case 'view':
                this.mostrarPassword();
                break;
        }

        this.limpiarEstado();
    }

    editarLicencia() {
        let url = '';
        if (this.idPendiente) {
            url = `/licencias/${this.idPendiente}/edit`;
        } else {
            const id = this.obtenerIdDeUrl();
            if (id) {
                url = `/licencias/${id}/edit`;
            }
        }
        
        if (url) {
            window.location.href = url;
        }
    }

    eliminarLicencia() {
        if (confirm('¿Está seguro de eliminar esta licencia?')) {
            const form = document.getElementById('deleteForm');
            if (form) {
                let url = '';
                if (this.idPendiente) {
                    url = `/licencias/${this.idPendiente}`;
                } else {
                    const id = this.obtenerIdDeUrl();
                    if (id) {
                        url = `/licencias/${id}`;
                    }
                }
                
                if (url) {
                    form.action = url;
                    form.submit();
                }
            }
        }
    }

    togglePassword() {
        if (!this.passwordVisible) {
            this.solicitarPassword('view');
        } else {
            this.ocultarPassword();
        }
    }

    mostrarPassword() {
        const passwordField = document.getElementById('passwordField');
        const passwordIcon = document.getElementById('passwordIcon');
        
        if (passwordField && passwordIcon) {
            passwordField.type = 'text';
            passwordIcon.className = 'fas fa-eye-slash';
            this.passwordVisible = true;
            
            // Ocultar automáticamente después de 15 segundos
            setTimeout(() => {
                if (this.passwordVisible) {
                    this.ocultarPassword();
                }
            }, 15000);
        }
    }

    ocultarPassword() {
        const passwordField = document.getElementById('passwordField');
        const passwordIcon = document.getElementById('passwordIcon');
        
        if (passwordField && passwordIcon) {
            passwordField.type = 'password';
            passwordIcon.className = 'fas fa-eye';
            this.passwordVisible = false;
        }
    }

    obtenerIdDeUrl() {
        const path = window.location.pathname;
        const match = path.match(/\/licencias\/(\d+)/);
        return match ? match[1] : null;
    }

    mostrarError(mensaje) {
        const errorDiv = document.getElementById('passwordError');
        if (errorDiv) {
            errorDiv.textContent = mensaje;
            errorDiv.style.display = 'block';
        }
    }

    limpiarEstado() {
        this.accionPendiente = null;
        this.idPendiente = null;
    }

    getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }
}

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.licenciasManager = new LicenciasManager();
});

// Funciones globales para compatibilidad
window.solicitarPassword = function(accion, id) {
    if (window.licenciasManager) {
        window.licenciasManager.solicitarPassword(accion, id);
    }
};

window.confirmarPassword = function() {
    if (window.licenciasManager) {
        window.licenciasManager.confirmarPassword();
    }
};

window.togglePassword = function() {
    if (window.licenciasManager) {
        window.licenciasManager.togglePassword();
    }
};
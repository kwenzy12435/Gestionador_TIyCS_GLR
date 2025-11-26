/**
 * App entry (Vite)
 * - Bootstrap + Icons
 * - SCSS principal
 * - Dashboard (gráficas/widgets)
 * - Tom Select para selects con búsqueda
 */

import * as bootstrap from 'bootstrap';
import 'bootstrap-icons/font/bootstrap-icons.css';
import '../scss/app.scss';

import './dashboard';
import TomSelect from 'tom-select';

// Hacer bootstrap disponible globalmente
window.bootstrap = bootstrap;

// Utilidad: NodeList -> Array
const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

/* ==========================================
 * 1) Tooltips / Popovers / Sidebar / Alerts / TomSelect
 * ========================================== */
document.addEventListener('DOMContentLoaded', () => {
  // Tooltips
  $$('[data-bs-toggle="tooltip"]').forEach(el => {
    new bootstrap.Tooltip(el, { boundary: 'window' });
  });

  // Popovers
  $$('[data-bs-toggle="popover"]').forEach(el => {
    new bootstrap.Popover(el, { sanitize: true });
  });

  // Cerrar sidebar al hacer click en un link
  const sidebarEl = document.getElementById('appSidebar');
  if (sidebarEl) {
    sidebarEl.addEventListener('click', (e) => {
      const link = e.target.closest('.app-menu .nav-link');
      if (!link) return;
      const off = bootstrap.Offcanvas.getInstance(sidebarEl);
      if (off) off.hide();
    });
  }

  // Auto-dismiss de alertas flash
  $$('.alert.auto-dismiss').forEach((el) => {
    const inst = bootstrap.Alert.getOrCreateInstance(el);
    setTimeout(() => inst.close(), 4500);
  });

  // Inicializador genérico de Tom Select
  const initTomSelect = (id, placeholder = 'Seleccionar…') => {
    const el = document.getElementById(id);
    if (!el) return null;

    if (el.dataset.tsInit === '1') return null;

    const ts = new TomSelect(el, {
      placeholder,
      allowEmptyOption: true,
      create: false,
      persist: false,
      maxOptions: 10000,
      diacritics: true,
      sortField: [{ field: 'text', direction: 'asc' }],
      dropdownParent: 'body',
      plugins: { clear_button: { title: 'Limpiar' } },
      render: {
        option: (data, escape) => `<div>${escape(data.text)}</div>`,
        item:   (data, escape) => `<div>${escape(data.text)}</div>`
      }
    });

    el.dataset.tsInit = '1';
    return ts;
  };

  // Selects más comunes
  initTomSelect('colaborador_id', 'Buscar colaborador…');
  ['canal_id', 'naturaleza_id', 'usuario_ti_id'].forEach(id => {
    initTomSelect(id, 'Seleccionar…');
  });
});

/* ==========================================
 * 2) Loader global sencillo (usa #appLoader)
 * ========================================== */
const AppLoader = (() => {
  let el = null;
  const getEl = () => {
    if (!el) el = document.getElementById('appLoader');
    return el;
  };
  return {
    show() {
      const loader = getEl();
      if (loader) loader.classList.remove('d-none');
    },
    hide() {
      const loader = getEl();
      if (loader) loader.classList.add('d-none');
    }
  };
})();

// Asegura que el loader empiece oculto
document.addEventListener('DOMContentLoaded', () => AppLoader.hide());

// Para cuando vuelves con el botón "atrás" del navegador
window.addEventListener('pageshow', () => AppLoader.hide());

/* ==========================================
 * 3) Confirmación global + loader
 *    - Se aplica a TODOS los forms DELETE
 *    - Y a cualquier form con data-confirm="mensaje..."
 * ========================================== */
document.addEventListener('DOMContentLoaded', () => {
  const modalEl   = document.getElementById('confirmModal');
  const okBtn     = document.getElementById('confirmOk');
  const titleEl   = document.getElementById('confirmTitle');
  const msgEl     = document.getElementById('confirmMessage');

  if (!modalEl || !okBtn || !titleEl || !msgEl) return;

  const modal = new bootstrap.Modal(modalEl, {
    backdrop: 'static',
    keyboard: false,
  });

  let formToSubmit = null;

  // Intercepta submits
  document.body.addEventListener('submit', (event) => {
    const form = event.target;
    if (!(form instanceof HTMLFormElement)) return;

    // ¿Es DELETE?
    const methodAttr  = (form.getAttribute('method') || 'GET').toUpperCase();
    const hasSpoofDel = form.querySelector('input[name="_method"][value="DELETE"]') !== null;
    const isDelete    = methodAttr === 'DELETE' || hasSpoofDel;

    // ¿Tiene data-confirm explícito?
    const hasDataConfirm = form.hasAttribute('data-confirm');

    // ¿Requerimos confirmación?
    const requireConfirm = isDelete || hasDataConfirm;

    // Permitir saltarse confirmación
    if (!requireConfirm ||
        form.dataset.noConfirm === 'true' ||
        form.dataset.confirmed === '1') {

      // Si el form pide loader explícito
      if (form.dataset.loading === 'true') {
        AppLoader.show();
      }
      return;
    }

    event.preventDefault();

    const title = form.dataset.confirmTitle ||
      (isDelete ? 'Eliminar registro' : 'Confirmar acción');

    const message = form.dataset.confirm ||
      (isDelete ? '¿Seguro que deseas eliminar este registro?' : '¿Seguro que deseas continuar?');

    const yesText = form.dataset.confirmYes ||
      (isDelete ? 'Sí, eliminar' : 'Sí, continuar');

    const variant = form.dataset.confirmVariant ||
      (isDelete ? 'danger' : 'primary');

    titleEl.textContent = title;
    msgEl.textContent   = message;
    okBtn.textContent   = yesText;
    okBtn.className     = `btn btn-${variant}`;

    formToSubmit = form;
    modal.show();
  });

  // Al confirmar
  okBtn.addEventListener('click', () => {
    if (!formToSubmit) {
      modal.hide();
      return;
    }

    // Evita bucle
    formToSubmit.dataset.confirmed = '1';

    // Mostrar loader salvo que se desactive
    if (formToSubmit.dataset.noLoader !== 'true') {
      AppLoader.show();
    }

    modal.hide();

    // Pequeño delay para que cierre visualmente el modal
    setTimeout(() => {
      formToSubmit.requestSubmit();
      formToSubmit = null;
    }, 50);
  });

  // Si se cierra el modal sin confirmar
  modalEl.addEventListener('hidden.bs.modal', () => {
    // Si no hay forms pendientes, asegúrate de ocultar loader
    if (!document.querySelector('.modal.show')) {
      formToSubmit = null;
      AppLoader.hide();
    }
  });

  // Loader para enlaces / botones que lo pidan
  document.body.addEventListener('click', (ev) => {
    const el = ev.target.closest('[data-loading="true"]');
    if (!el) return;

    // Si está dentro de un form con confirmación, el loader lo maneja el submit
    if (el.closest('form[data-confirm]')) return;

    AppLoader.show();
  });
});

export { AppLoader };

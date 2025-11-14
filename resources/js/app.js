/**
 * App entry (Vite)
 * - Bootstrap + Icons
 * - SCSS principal
 * - Dashboard (gráficas/widgets)
 * - Tom Select para selects con búsqueda
 */

import * as bootstrap from 'bootstrap';             // ✅ acceso a Tooltip/Offcanvas/etc.
import 'bootstrap-icons/font/bootstrap-icons.css';
import '../scss/app.scss';

import './dashboard';
import TomSelect from 'tom-select';

// Haz bootstrap disponible globalmente si otros scripts lo usan
window.bootstrap = bootstrap;

// Utilidad: convierte NodeList a Array
const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

document.addEventListener('DOMContentLoaded', () => {
  // ==============================
  // 1) Tooltips / Popovers
  // ==============================
  $$( '[data-bs-toggle="tooltip"]' ).forEach(el => {
    new bootstrap.Tooltip(el, { boundary: 'window' });
  });
  $$( '[data-bs-toggle="popover"]' ).forEach(el => {
    new bootstrap.Popover(el, { sanitize: true });
  });

  // ==========================================
  // 2) Cerrar sidebar (offcanvas) al navegar
  // ==========================================
  const sidebarEl = document.getElementById('appSidebar');
  if (sidebarEl) {
    sidebarEl.addEventListener('click', (e) => {
      const link = e.target.closest('.app-menu .nav-link');
      if (!link) return;
      const off = bootstrap.Offcanvas.getInstance(sidebarEl);
      if (off) off.hide();
    });
  }

  // ==========================================
  // 3) Auto-dismiss de alertas flash (opcional)
  //    Agrega class="auto-dismiss" en tu partial
  // ==========================================
  $$('.alert.auto-dismiss').forEach((el) => {
    const inst = bootstrap.Alert.getOrCreateInstance(el);
    setTimeout(() => inst.close(), 4500);
  });

  // ==========================================
  // 4) Tom Select (selects con búsqueda)
  //    Mantiene placeholders y respeta opción vacía
  // ==========================================
  const initTomSelect = (id, placeholder = 'Seleccionar…') => {
    const el = document.getElementById(id);
    if (!el) return null;

    // Si ya fue inicializado, no repetir
    if (el.dataset.tsInit === '1') return null;

    const ts = new TomSelect(el, {
      placeholder,
      allowEmptyOption: true,
      create: false,
      persist: false,
      maxOptions: 10000,
      diacritics: true,
      sortField: [{ field: 'text', direction: 'asc' }],
      dropdownParent: 'body', // evita issues de z-index dentro de modals/offcanvas
      plugins: { clear_button: { title: 'Limpiar' } },
      render: {
        option: (data, escape) => `<div>${escape(data.text)}</div>`,
        item:   (data, escape) => `<div>${escape(data.text)}</div>`
      }
    });

    el.dataset.tsInit = '1';
    return ts;
  };

  // Colaborador (con buscador)
  initTomSelect('colaborador_id', 'Buscar colaborador…');

  // Otros selects comunes del sistema (si existen en la vista actual)
  ['canal_id', 'naturaleza_id', 'usuario_ti_id'].forEach(id => {
    initTomSelect(id, 'Seleccionar…');
  });
});

// ==============================
// 5) Loader global (overlay)
// ==============================
const AppLoader = (() => {
  const el = document.getElementById('globalLoader');
  const show = () => { if (el) el.classList.remove('is-hide'); };
  const hide = () => { if (el) el.classList.add('is-hide'); };
  return { show, hide };
})();

// Asegura que el loader quede oculto al cargar
document.addEventListener('DOMContentLoaded', () => AppLoader.hide());

// ==============================
// 6) Confirm Modal + interceptores
// ==============================
document.addEventListener('DOMContentLoaded', () => {
  const modalEl = document.getElementById('confirmModal');
  if (!modalEl) return;

  const titleEl = document.getElementById('confirmTitle');
  const textEl  = document.getElementById('confirmText');
  const okBtn   = document.getElementById('confirmOk');

  const bsModal = new bootstrap.Modal(modalEl, { backdrop: 'static', keyboard: false });

  let resolving = null;
  let confirmed = false;

  function askConfirm({ title, text, yesText = 'Sí, continuar', variant = 'primary' }) {
    return new Promise((resolve) => {
      resolving = resolve;
      confirmed = false;

      if (titleEl) titleEl.textContent = title || '¿Confirmar acción?';
      if (textEl)  textEl.textContent  = text  || 'Esta acción no se puede deshacer.';

      // reset + variante de botón
      okBtn.className = 'btn';
      okBtn.classList.add(`btn-${variant || 'primary'}`);
      okBtn.textContent = yesText;

      bsModal.show();
    });
  }

  okBtn?.addEventListener('click', () => {
    confirmed = true;
    bsModal.hide();
    if (resolving) resolving(true);
  });
  modalEl.addEventListener('hidden.bs.modal', () => {
    if (!confirmed && resolving) resolving(false);
    resolving = null;
  });

  // Intercepta formularios con data-confirm (y muestra loader por defecto)
  document.addEventListener('submit', async (ev) => {
    const form = ev.target;
    if (!(form instanceof HTMLFormElement)) return;

    // Loader por defecto (desactiva con data-no-loader="true")
    if (!form.dataset.noLoader) AppLoader.show();

    if (form.matches('[data-confirm]') && !form.dataset.confirmed) {
      ev.preventDefault(); // detén envío
      const ok = await askConfirm({
        title:   form.dataset.confirmTitle || '¿Confirmar acción?',
        text:    form.dataset.confirm || 'Esta acción no se puede deshacer.',
        yesText: form.dataset.confirmYes || 'Sí, continuar',
        variant: form.dataset.confirmVariant || 'danger',
      });
      if (ok) {
        form.dataset.confirmed = '1'; // evita loop
        form.submit();
      } else {
        AppLoader.hide();
      }
    }
  });

  // Enlaces que deben mostrar loader
  document.addEventListener('click', (ev) => {
    const a = ev.target.closest('a[href]');
    if (!a) return;
    if (a.dataset.noLoader) return;
    if (a.dataset.loading === 'true') {
      AppLoader.show();
    }
  });
});

export { AppLoader };

// ==============================
// CONFIRMACIÓN GLOBAL + LOADER (FIX)
// ==============================
(() => {
  const $  = (sel, root = document) => root.querySelector(sel);
  const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

  // Loader helpers
  const showLoader = () => { $('#appLoader')?.classList.remove('d-none'); };
  const hideLoader = () => { $('#appLoader')?.classList.add('d-none'); };

  // Limpia backdrops y estado del body (bootstrap)
  const cleanupBackdrops = () => {
    $$('.modal-backdrop').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('paddingRight');
    document.body.style.removeProperty('overflow');
  };

  // Modal de confirmación
  const modalEl = document.getElementById('confirmModal');
  const modal   = modalEl ? new window.bootstrap.Modal(modalEl, { backdrop: 'static' }) : null;
  const titleEl = document.getElementById('confirmTitle');
  const msgEl   = document.getElementById('confirmMessage');
  const okBtn   = document.getElementById('confirmOk');
  const cancelB = document.getElementById('confirmCancel');

  let pendingForm = null;

  // (1) Interceptar formularios marcados con data-confirm
  document.addEventListener('submit', (ev) => {
    const form = ev.target.closest('form[data-confirm]');
    if (!form) return;

    // Si ya fue confirmado, deja fluir
    if (form.dataset.confirmed === '1') return;

    ev.preventDefault();

    // Configurar modal con textos
    titleEl.textContent  = form.dataset.confirmTitle || 'Confirmar';
    msgEl.textContent    = form.dataset.confirm || '¿Estás seguro?';
    const variant        = form.dataset.confirmVariant || 'danger';
    okBtn.className      = `btn btn-${variant}`;
    okBtn.textContent    = form.dataset.confirmYes || 'Sí, continuar';

    pendingForm = form;
    modal?.show();
  });

  // (2) Confirmar => mostrar loader (si se pidió) y enviar
  okBtn?.addEventListener('click', () => {
    if (!pendingForm) return;

    // Mostrar loader SOLO al confirmar
    if (pendingForm.dataset.loading === 'true') showLoader();

    pendingForm.dataset.confirmed = '1';
    modal?.hide();

    // Pequeño delay para que Bootstrap cierre el modal
    setTimeout(() => pendingForm.requestSubmit(), 10);
  });

  // (3) Cancelar => limpiar y ocultar loader
  const onCancel = () => {
    pendingForm = null;
    hideLoader();
    cleanupBackdrops();
  };
  cancelB?.addEventListener('click', onCancel);

  // (4) Al cerrar cualquier modal, asegurar limpieza
  modalEl?.addEventListener('hidden.bs.modal', onCancel);
  document.addEventListener('hidden.bs.modal', () => {
    // Si no queda ningún modal abierto
    if (!document.querySelector('.modal.show')) cleanupBackdrops();
    hideLoader();
  });

  // (5) Click en cualquier botón de cierre de modal
  document.addEventListener('click', (e) => {
    if (e.target.closest('[data-bs-dismiss="modal"]')) {
      onCancel();
    }
  });

  // (6) Loader para acciones directas (enlaces/botones) que NO abren confirmación
  document.addEventListener('click', (ev) => {
    const el = ev.target.closest('[data-loading="true"]');
    if (!el) return;

    // Si está dentro de un form con confirmación, no mostrar loader aún
    if (el.closest('form[data-confirm]')) return;

    // Si el click ocurre dentro de un modal, no bloquees hasta que realmente se envíe algo
    if (el.closest('.modal')) return;

    showLoader();
  });

  // (7) ESC también limpia por si acaso
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') onCancel();
  });

  // (8) Al volver con el historial, asegúrate de esconder loader
  window.addEventListener('pageshow', hideLoader);
})();

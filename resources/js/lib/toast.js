import Swal from 'sweetalert2';

// Rowaad-branded SweetAlert2 wrapper — used across every form for success/failure feedback.
// Colors match the palette: primary teal #3DAFB9, dark navy #2D4B7E, danger red #DC2626.

const BRAND = '#3DAFB9';
const NAVY  = '#2D4B7E';
const DANGER = '#DC2626';
const WARN = '#F59E0B';

const rtl = () => (document.documentElement?.getAttribute('dir') || 'ltr') === 'rtl';

const baseConfig = () => ({
    confirmButtonColor: BRAND,
    cancelButtonColor:  '#9CA3AF',
    background: '#FFFFFF',
    color: NAVY,
    customClass: {
        popup: 'rowaad-swal-popup',
        title: 'rowaad-swal-title',
        htmlContainer: 'rowaad-swal-body',
        confirmButton: 'rowaad-swal-confirm',
    },
    heightAuto: false,
    reverseButtons: rtl(),
});

export const toast = Swal.mixin({
    toast: true,
    position: rtl() ? 'top-start' : 'top-end',
    showConfirmButton: false,
    timer: 3800,
    timerProgressBar: true,
    background: '#FFFFFF',
    color: NAVY,
    customClass: { popup: 'rowaad-toast-popup' },
    didOpen: (el) => {
        el.addEventListener('mouseenter', Swal.stopTimer);
        el.addEventListener('mouseleave', Swal.resumeTimer);
    },
});

export const notifySuccess = (title, text = '') =>
    Swal.fire({
        ...baseConfig(),
        icon: 'success',
        iconColor: BRAND,
        title,
        text,
        confirmButtonText: 'ممتاز',
        timer: 4500,
        timerProgressBar: true,
    });

export const notifyError = (title, text = '') =>
    Swal.fire({
        ...baseConfig(),
        icon: 'error',
        iconColor: DANGER,
        confirmButtonColor: DANGER,
        title,
        text,
        confirmButtonText: 'حسناً',
    });

export const notifyWarn = (title, text = '') =>
    Swal.fire({
        ...baseConfig(),
        icon: 'warning',
        iconColor: WARN,
        confirmButtonColor: WARN,
        title,
        text,
        confirmButtonText: 'حسناً',
    });

export const notifyInfo = (title, text = '') =>
    Swal.fire({
        ...baseConfig(),
        icon: 'info',
        iconColor: BRAND,
        title,
        text,
        confirmButtonText: 'حسناً',
    });

export const confirmAction = ({ title, text, confirmText = 'نعم، تأكيد', cancelText = 'إلغاء', danger = false } = {}) =>
    Swal.fire({
        ...baseConfig(),
        icon: danger ? 'warning' : 'question',
        iconColor: danger ? DANGER : BRAND,
        confirmButtonColor: danger ? DANGER : BRAND,
        title,
        text,
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
    }).then((r) => r.isConfirmed);

// Toast variants — small corner popups (auto-dismiss)
export const toastSuccess = (title) => toast.fire({ icon: 'success', title, iconColor: BRAND });
export const toastError   = (title) => toast.fire({ icon: 'error',   title, iconColor: DANGER });
export const toastInfo    = (title) => toast.fire({ icon: 'info',    title, iconColor: BRAND });

export default { notifySuccess, notifyError, notifyWarn, notifyInfo, confirmAction, toastSuccess, toastError, toastInfo };

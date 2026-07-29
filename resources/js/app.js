import '../css/app.css';
import { createApp, h } from 'vue';
import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { notifySuccess, notifyError, toastSuccess, toastError } from './lib/toast';

createInertiaApp({
    title: (title) => (title ? `${title} — رواد بلا حدود` : 'رواد بلا حدود للإستشارات الإقتصادية'),
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue);

        // Expose toast helpers globally for any Vue component
        app.config.globalProperties.$toast = {
            success: notifySuccess, error: notifyError,
            toastSuccess, toastError,
        };

        app.mount(el);
    },
    progress: {
        color: '#3DAFB9',
    },
});

// Global Inertia flash-message → SweetAlert2 bridge.
// Any controller returning `->with('success', '...')` or `->with('error', '...')`
// automatically triggers a branded modal. No per-form wiring needed.
router.on('success', (event) => {
    const flash = event?.detail?.page?.props?.flash;
    if (!flash) return;
    if (flash.success) notifySuccess('تم بنجاح', flash.success);
    if (flash.error)   notifyError('حدث خطأ',  flash.error);
});

// Inertia form-validation errors → toast
router.on('invalid', (event) => {
    toastError('راجع الحقول المميّزة بالأحمر');
});


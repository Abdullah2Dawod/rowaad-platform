import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

/**
 * Laravel-backed i18n composable.
 * Translations come from Inertia shared props (see HandleInertiaRequests).
 *
 * Usage:
 *   const { t, locale, direction, switchLocale } = useI18n();
 *   {{ t('nav.home') }}
 *   {{ t('services.economic.title') }}
 */
export function useI18n() {
    const page = usePage();

    const locale    = computed(() => page.props.locale || 'ar');
    const direction = computed(() => page.props.direction || 'rtl');
    const isRtl     = computed(() => direction.value === 'rtl');

    // Get nested value from translations object by dot-notation key
    const t = (key, fallback = null) => {
        const dict = page.props.translations || {};
        const parts = key.split('.');
        let val = dict;
        for (const p of parts) {
            if (val && typeof val === 'object' && p in val) {
                val = val[p];
            } else {
                return fallback ?? key;
            }
        }
        return typeof val === 'string' ? val : (fallback ?? key);
    };

    const switchLocale = (newLocale) => {
        // Full page reload — Laravel session picks up new locale
        window.location.href = `/locale/${newLocale}`;
    };

    return { t, locale, direction, isRtl, switchLocale };
}

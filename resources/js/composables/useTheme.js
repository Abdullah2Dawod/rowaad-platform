import { ref, watch, onMounted } from 'vue';

const STORAGE_KEY = 'rowaad-theme';
const isDark = ref(false);

const applyTheme = (dark) => {
    const root = document.documentElement;
    root.classList.add('theme-transition');
    if (dark) root.classList.add('dark');
    else root.classList.remove('dark');
    // Remove transition class after animation completes
    setTimeout(() => root.classList.remove('theme-transition'), 500);
};

export function useTheme() {
    onMounted(() => {
        const saved = localStorage.getItem(STORAGE_KEY);
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        isDark.value = saved === 'dark' || (saved === null && prefersDark);
        applyTheme(isDark.value);
    });

    watch(isDark, (val) => {
        applyTheme(val);
        localStorage.setItem(STORAGE_KEY, val ? 'dark' : 'light');
    });

    const toggleTheme = () => { isDark.value = !isDark.value; };

    return { isDark, toggleTheme };
}

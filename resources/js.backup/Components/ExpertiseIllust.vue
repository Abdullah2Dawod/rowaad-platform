<template>
    <div class="w-full h-full flex items-center justify-center">
        <!-- 0: Economic Consulting — Chart with growing bars & trend line -->
        <svg v-if="variant === 0" viewBox="0 0 400 400" class="w-full h-auto max-w-md" fill="none">
            <defs>
                <linearGradient :id="`grad-econ-${uid}`" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" :stop-color="colors.primary" stop-opacity="1"/>
                    <stop offset="100%" :stop-color="colors.primary" stop-opacity="0.15"/>
                </linearGradient>
                <linearGradient :id="`grad-econ2-${uid}`" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" :stop-color="colors.secondary"/>
                    <stop offset="100%" :stop-color="colors.primary"/>
                </linearGradient>
            </defs>
            <!-- Grid backdrop -->
            <g :stroke="colors.grid" stroke-width="1" opacity="0.4">
                <line v-for="y in [80, 150, 220, 290]" :key="y" x1="40" :y1="y" x2="360" :y2="y" stroke-dasharray="2 6"/>
            </g>
            <!-- Bars -->
            <g>
                <rect x="60"  y="240" width="30" height="100" rx="6" :fill="`url(#grad-econ-${uid})`" opacity="0.7"/>
                <rect x="105" y="200" width="30" height="140" rx="6" :fill="`url(#grad-econ-${uid})`" opacity="0.8"/>
                <rect x="150" y="170" width="30" height="170" rx="6" :fill="`url(#grad-econ-${uid})`" opacity="0.9"/>
                <rect x="195" y="130" width="30" height="210" rx="6" :fill="`url(#grad-econ-${uid})`" opacity="0.95"/>
                <rect x="240" y="110" width="30" height="230" rx="6" :fill="`url(#grad-econ-${uid})`" opacity="1"/>
                <rect x="285" y="70"  width="30" height="270" rx="6" :fill="`url(#grad-econ-${uid})`" opacity="1"/>
            </g>
            <!-- Trend line -->
            <path d="M75 250 L120 210 L165 175 L210 135 L255 115 L300 75" :stroke="`url(#grad-econ2-${uid})`" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
            <!-- Data points -->
            <circle cx="75" cy="250" r="5" :fill="colors.secondary"/>
            <circle cx="120" cy="210" r="5" :fill="colors.secondary"/>
            <circle cx="165" cy="175" r="5" :fill="colors.secondary"/>
            <circle cx="210" cy="135" r="5" :fill="colors.secondary"/>
            <circle cx="255" cy="115" r="5" :fill="colors.secondary"/>
            <circle cx="300" cy="75" r="7" :fill="colors.primary">
                <animate attributeName="r" values="7;11;7" dur="2s" repeatCount="indefinite"/>
            </circle>
            <!-- Growth arrow badge -->
            <g transform="translate(315,50)">
                <rect x="-5" y="-15" width="60" height="30" rx="15" :fill="colors.primary" opacity="0.15"/>
                <path d="M8 5 L12 -2 L18 3" :stroke="colors.primary" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                <text x="30" y="4" :fill="colors.primary" font-size="12" font-weight="700">+27%</text>
            </g>
        </svg>

        <!-- 1: Feasibility Studies — Document stack with magnifier -->
        <svg v-else-if="variant === 1" viewBox="0 0 400 400" class="w-full h-auto max-w-md" fill="none">
            <defs>
                <linearGradient :id="`grad-doc-${uid}`" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" :stop-color="colors.docFill"/>
                    <stop offset="100%" :stop-color="colors.docFillEnd"/>
                </linearGradient>
            </defs>
            <!-- Back document -->
            <g transform="rotate(-8 200 200)">
                <rect x="80" y="60" width="200" height="260" rx="14" :fill="`url(#grad-doc-${uid})`" opacity="0.55" :stroke="colors.primary" stroke-width="1.5" stroke-opacity="0.4"/>
                <line x1="105" y1="105" x2="245" y2="105" :stroke="colors.primary" stroke-width="2" opacity="0.35"/>
                <line x1="105" y1="130" x2="215" y2="130" :stroke="colors.primary" stroke-width="2" opacity="0.35"/>
                <line x1="105" y1="155" x2="235" y2="155" :stroke="colors.primary" stroke-width="2" opacity="0.35"/>
            </g>
            <!-- Front document -->
            <rect x="90" y="80" width="200" height="260" rx="14" :fill="`url(#grad-doc-${uid})`" :stroke="colors.primary" stroke-width="2" stroke-opacity="0.7"/>
            <!-- Ribbon -->
            <path d="M240 80 L260 80 L260 130 L250 118 L240 130 Z" :fill="colors.primary"/>
            <!-- Content lines -->
            <line x1="115" y1="130" x2="230" y2="130" :stroke="colors.primary" stroke-width="3" stroke-linecap="round"/>
            <line x1="115" y1="150" x2="200" y2="150" :stroke="colors.textLight" stroke-width="2" stroke-linecap="round"/>
            <line x1="115" y1="170" x2="225" y2="170" :stroke="colors.textLight" stroke-width="2" stroke-linecap="round"/>
            <!-- Small chart inside document -->
            <rect x="115" y="200" width="160" height="80" rx="8" :fill="colors.chartBg" opacity="0.4"/>
            <path d="M125 260 L155 240 L185 225 L215 210 L245 195 L265 190" :stroke="colors.primary" stroke-width="2.5" fill="none" stroke-linecap="round"/>
            <circle cx="265" cy="190" r="4" :fill="colors.primary"/>
            <!-- Check items at bottom -->
            <g>
                <circle cx="120" cy="305" r="7" :fill="colors.primary" opacity="0.15"/>
                <path d="M116 305 L119 308 L124 302" :stroke="colors.primary" stroke-width="2" fill="none" stroke-linecap="round"/>
                <line x1="135" y1="305" x2="230" y2="305" :stroke="colors.textLight" stroke-width="2" stroke-linecap="round"/>
            </g>
            <!-- Magnifier overlay -->
            <g transform="translate(280,240)">
                <circle cx="0" cy="0" r="42" :fill="colors.bgSoft" opacity="0.6"/>
                <circle cx="0" cy="0" r="42" :stroke="colors.primary" stroke-width="4" fill="none"/>
                <line x1="30" y1="30" x2="55" y2="55" :stroke="colors.primary" stroke-width="6" stroke-linecap="round"/>
                <circle cx="-8" cy="-8" r="8" :fill="colors.primary" opacity="0.25"/>
                <animateTransform attributeName="transform" type="translate" values="280,240; 285,235; 280,240; 275,245; 280,240" dur="6s" repeatCount="indefinite" additive="replace"/>
            </g>
        </svg>

        <!-- 2: Strategic Planning — Target with arrows & pathways -->
        <svg v-else-if="variant === 2" viewBox="0 0 400 400" class="w-full h-auto max-w-md" fill="none">
            <!-- Concentric circles -->
            <circle cx="200" cy="200" r="140" :stroke="colors.primary" stroke-width="1.5" fill="none" opacity="0.20" stroke-dasharray="3 8"/>
            <circle cx="200" cy="200" r="105" :stroke="colors.primary" stroke-width="1.5" fill="none" opacity="0.35"/>
            <circle cx="200" cy="200" r="70" :stroke="colors.primary" stroke-width="2" fill="none" opacity="0.55"/>
            <circle cx="200" cy="200" r="35" :fill="colors.primary" opacity="0.15" :stroke="colors.primary" stroke-width="2"/>
            <circle cx="200" cy="200" r="12" :fill="colors.primary">
                <animate attributeName="r" values="12;18;12" dur="2.5s" repeatCount="indefinite"/>
            </circle>
            <!-- Rotating dashed ring -->
            <g style="transform-origin: 200px 200px;">
                <circle cx="200" cy="200" r="175" :stroke="colors.secondary" stroke-width="1" fill="none" opacity="0.30" stroke-dasharray="4 12"/>
                <animateTransform attributeName="transform" type="rotate" from="0 200 200" to="360 200 200" dur="40s" repeatCount="indefinite"/>
            </g>
            <!-- Arrows converging to center -->
            <g :stroke="colors.secondary" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path d="M60 60 L155 155 M155 155 L150 140 M155 155 L140 150"/>
                <path d="M340 60 L245 155 M245 155 L245 140 M245 155 L260 155"/>
                <path d="M60 340 L155 245 M155 245 L155 260 M155 245 L140 245"/>
                <path d="M340 340 L245 245 M245 245 L260 245 M245 245 L245 260"/>
            </g>
            <!-- Corner labels -->
            <g :fill="colors.primary" font-size="11" font-weight="700" opacity="0.7">
                <text x="30" y="45">01</text>
                <text x="345" y="45">02</text>
                <text x="30" y="365">03</text>
                <text x="345" y="365">04</text>
            </g>
        </svg>

        <!-- 3: Business Development — Rocket / growth flow -->
        <svg v-else-if="variant === 3" viewBox="0 0 400 400" class="w-full h-auto max-w-md" fill="none">
            <defs>
                <linearGradient :id="`grad-rocket-${uid}`" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" :stop-color="colors.secondary"/>
                    <stop offset="100%" :stop-color="colors.primary"/>
                </linearGradient>
            </defs>
            <!-- Trajectory curve -->
            <path d="M50 350 Q 150 340 200 250 T 350 60" :stroke="colors.primary" stroke-width="2.5" stroke-dasharray="4 8" fill="none" opacity="0.6" stroke-linecap="round"/>
            <!-- Cloud puffs (small trail) -->
            <g opacity="0.35">
                <circle cx="80" cy="340" r="8" :fill="colors.primary"/>
                <circle cx="110" cy="330" r="6" :fill="colors.primary"/>
                <circle cx="140" cy="310" r="7" :fill="colors.primary"/>
            </g>
            <!-- Milestone dots -->
            <g>
                <circle cx="130" cy="310" r="7" :fill="colors.secondary" opacity="0.8"/>
                <circle cx="200" cy="250" r="7" :fill="colors.secondary" opacity="0.8"/>
                <circle cx="275" cy="150" r="7" :fill="colors.secondary" opacity="0.8"/>
            </g>
            <!-- Rocket at top-right -->
            <g transform="translate(310,80)">
                <path d="M0 20 Q 5 -20 20 -25 Q 35 -20 40 20 L30 25 L25 40 L15 40 L10 25 Z" :fill="`url(#grad-rocket-${uid})`"/>
                <circle cx="20" cy="0" r="7" :fill="colors.bg"/>
                <circle cx="20" cy="0" r="4" :fill="colors.primary"/>
                <!-- Fins -->
                <path d="M5 15 L-5 30 L10 25 Z" :fill="colors.primary" opacity="0.85"/>
                <path d="M35 15 L45 30 L30 25 Z" :fill="colors.primary" opacity="0.85"/>
                <!-- Flame -->
                <path d="M12 40 Q 15 55 20 65 Q 25 55 28 40 Z" :fill="colors.flame" opacity="0.9">
                    <animate attributeName="d" values="M12 40 Q 15 55 20 65 Q 25 55 28 40 Z;M12 40 Q 14 60 20 72 Q 26 60 28 40 Z;M12 40 Q 15 55 20 65 Q 25 55 28 40 Z" dur="0.3s" repeatCount="indefinite"/>
                </path>
                <animateTransform attributeName="transform" type="translate" values="310,80; 314,76; 310,80; 306,84; 310,80" dur="1.5s" repeatCount="indefinite"/>
            </g>
            <!-- Small stars in background -->
            <g :fill="colors.primary" opacity="0.4">
                <circle cx="60" cy="80" r="2"/>
                <circle cx="90" cy="120" r="1.5"/>
                <circle cx="140" cy="100" r="1.5"/>
                <circle cx="230" cy="80" r="2"/>
                <circle cx="290" cy="200" r="1.5"/>
                <circle cx="350" cy="180" r="1.5"/>
            </g>
            <!-- Metric badge -->
            <g transform="translate(70,180)">
                <rect x="-5" y="-20" width="105" height="40" rx="20" :fill="colors.primary" opacity="0.10" :stroke="colors.primary" stroke-width="1.5"/>
                <text x="8" y="6" :fill="colors.primary" font-size="14" font-weight="800">GROWTH</text>
                <text x="72" y="6" :fill="colors.secondary" font-size="14" font-weight="800">↑</text>
            </g>
        </svg>

        <!-- 4: Financial Consulting — Stacked coins + wallet -->
        <svg v-else-if="variant === 4" viewBox="0 0 400 400" class="w-full h-auto max-w-md" fill="none">
            <defs>
                <linearGradient :id="`grad-coin-${uid}`" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" :stop-color="colors.secondary"/>
                    <stop offset="100%" :stop-color="colors.primary"/>
                </linearGradient>
            </defs>
            <!-- Base line -->
            <line x1="50" y1="340" x2="350" y2="340" :stroke="colors.primary" stroke-width="1" opacity="0.30" stroke-dasharray="3 6"/>
            <!-- Coin stacks -->
            <g>
                <!-- Stack 1 (short) -->
                <g transform="translate(90,320)">
                    <ellipse cx="0" cy="0" rx="28" ry="8" :fill="colors.primary" opacity="0.3"/>
                    <ellipse cx="0" cy="-14" rx="28" ry="8" :fill="`url(#grad-coin-${uid})`"/>
                    <ellipse cx="0" cy="-28" rx="28" ry="8" :fill="`url(#grad-coin-${uid})`"/>
                </g>
                <!-- Stack 2 (medium) -->
                <g transform="translate(160,320)">
                    <ellipse cx="0" cy="0"  rx="28" ry="8" :fill="colors.primary" opacity="0.3"/>
                    <ellipse cx="0" cy="-14" rx="28" ry="8" :fill="`url(#grad-coin-${uid})`"/>
                    <ellipse cx="0" cy="-28" rx="28" ry="8" :fill="`url(#grad-coin-${uid})`"/>
                    <ellipse cx="0" cy="-42" rx="28" ry="8" :fill="`url(#grad-coin-${uid})`"/>
                </g>
                <!-- Stack 3 (tallest) -->
                <g transform="translate(230,320)">
                    <ellipse cx="0" cy="0"  rx="28" ry="8" :fill="colors.primary" opacity="0.3"/>
                    <ellipse cx="0" cy="-14" rx="28" ry="8" :fill="`url(#grad-coin-${uid})`"/>
                    <ellipse cx="0" cy="-28" rx="28" ry="8" :fill="`url(#grad-coin-${uid})`"/>
                    <ellipse cx="0" cy="-42" rx="28" ry="8" :fill="`url(#grad-coin-${uid})`"/>
                    <ellipse cx="0" cy="-56" rx="28" ry="8" :fill="`url(#grad-coin-${uid})`"/>
                    <text x="-5" y="-53" :fill="colors.bg" font-size="12" font-weight="800">$</text>
                </g>
                <!-- Stack 4 (small) -->
                <g transform="translate(300,320)">
                    <ellipse cx="0" cy="0"  rx="28" ry="8" :fill="colors.primary" opacity="0.3"/>
                    <ellipse cx="0" cy="-14" rx="28" ry="8" :fill="`url(#grad-coin-${uid})`"/>
                </g>
            </g>
            <!-- Trend line above coins -->
            <path d="M90 250 L160 220 L230 175 L300 245" :stroke="colors.primary" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="90" cy="250" r="5" :fill="colors.primary"/>
            <circle cx="160" cy="220" r="5" :fill="colors.primary"/>
            <circle cx="230" cy="175" r="7" :fill="colors.primary">
                <animate attributeName="r" values="7;10;7" dur="2s" repeatCount="indefinite"/>
            </circle>
            <circle cx="300" cy="245" r="5" :fill="colors.primary"/>
            <!-- Percentage badge at peak -->
            <g transform="translate(200,120)">
                <rect x="-40" y="-18" width="80" height="36" rx="18" :fill="colors.primary"/>
                <text x="-25" y="6" fill="#fff" font-size="15" font-weight="800">+48%</text>
            </g>
            <!-- Wallet at top-left -->
            <g transform="translate(60,100)">
                <rect x="0" y="0" width="70" height="50" rx="8" :fill="colors.primary" opacity="0.15" :stroke="colors.primary" stroke-width="2"/>
                <rect x="45" y="18" width="30" height="14" rx="3" :fill="colors.primary"/>
                <circle cx="63" cy="25" r="2.5" :fill="colors.bg"/>
            </g>
        </svg>

        <!-- 5: AI Solutions — Neural network / brain -->
        <svg v-else-if="variant === 5" viewBox="0 0 400 400" class="w-full h-auto max-w-md" fill="none">
            <defs>
                <linearGradient :id="`grad-ai-${uid}`" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" :stop-color="colors.secondary"/>
                    <stop offset="100%" :stop-color="colors.primary"/>
                </linearGradient>
            </defs>
            <!-- Central hexagonal CPU chip -->
            <g transform="translate(200,200)">
                <!-- Chip body -->
                <rect x="-45" y="-45" width="90" height="90" rx="14" :fill="`url(#grad-ai-${uid})`" opacity="0.90"/>
                <rect x="-45" y="-45" width="90" height="90" rx="14" :stroke="colors.primary" stroke-width="2" fill="none"/>
                <!-- Inner circuit -->
                <rect x="-25" y="-25" width="50" height="50" rx="6" :fill="colors.bg" opacity="0.85"/>
                <text x="-16" y="6" :fill="colors.primary" font-size="18" font-weight="900">AI</text>
                <!-- Chip legs -->
                <g :fill="colors.primary" opacity="0.85">
                    <rect x="-56" y="-25" width="10" height="6" rx="2"/>
                    <rect x="-56" y="-8" width="10" height="6" rx="2"/>
                    <rect x="-56" y="9" width="10" height="6" rx="2"/>
                    <rect x="46" y="-25" width="10" height="6" rx="2"/>
                    <rect x="46" y="-8" width="10" height="6" rx="2"/>
                    <rect x="46" y="9" width="10" height="6" rx="2"/>
                    <rect x="-25" y="-56" width="6" height="10" rx="2"/>
                    <rect x="-8" y="-56" width="6" height="10" rx="2"/>
                    <rect x="9" y="-56" width="6" height="10" rx="2"/>
                    <rect x="-25" y="46" width="6" height="10" rx="2"/>
                    <rect x="-8" y="46" width="6" height="10" rx="2"/>
                    <rect x="9" y="46" width="6" height="10" rx="2"/>
                </g>
            </g>
            <!-- Neural network nodes -->
            <g>
                <!-- Connections -->
                <g :stroke="colors.secondary" stroke-width="1.2" stroke-dasharray="3 5" opacity="0.55">
                    <line x1="80"  y1="90"  x2="155" y2="175"/>
                    <line x1="80"  y1="200" x2="155" y2="200"/>
                    <line x1="80"  y1="310" x2="155" y2="225"/>
                    <line x1="320" y1="90"  x2="245" y2="175"/>
                    <line x1="320" y1="200" x2="245" y2="200"/>
                    <line x1="320" y1="310" x2="245" y2="225"/>
                    <line x1="200" y1="60"  x2="200" y2="155"/>
                    <line x1="200" y1="340" x2="200" y2="245"/>
                </g>
                <!-- Nodes -->
                <g>
                    <circle cx="80"  cy="90"  r="10" :fill="colors.secondary"/>
                    <circle cx="80"  cy="200" r="10" :fill="colors.secondary"/>
                    <circle cx="80"  cy="310" r="10" :fill="colors.secondary"/>
                    <circle cx="320" cy="90"  r="10" :fill="colors.secondary"/>
                    <circle cx="320" cy="200" r="10" :fill="colors.secondary"/>
                    <circle cx="320" cy="310" r="10" :fill="colors.secondary"/>
                    <circle cx="200" cy="60"  r="10" :fill="colors.primary">
                        <animate attributeName="r" values="10;14;10" dur="2s" repeatCount="indefinite"/>
                    </circle>
                    <circle cx="200" cy="340" r="10" :fill="colors.primary">
                        <animate attributeName="r" values="10;14;10" dur="2.5s" repeatCount="indefinite"/>
                    </circle>
                </g>
            </g>
            <!-- Small labels -->
            <g :fill="colors.primary" font-size="9" font-weight="700" opacity="0.6">
                <text x="45" y="60" >INPUT</text>
                <text x="320" y="60">OUTPUT</text>
            </g>
        </svg>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    variant: { type: Number, default: 0 },
    isDark:  { type: Boolean, default: false },
});

// Unique id so gradient defs don't collide when multiple illustrations mount
const uid = Math.random().toString(36).slice(2, 9);

const colors = computed(() => props.isDark ? {
    primary: '#6BC8D2',
    secondary: '#95DCE3',
    grid: '#6BC8D2',
    bg: '#0F1F38',
    bgSoft: '#122440',
    docFill: '#0F1F38',
    docFillEnd: '#1A2F50',
    chartBg: '#122440',
    textLight: '#95DCE3',
    flame: '#F59E0B',
} : {
    primary: '#2D4B7E',
    secondary: '#3DAFB9',
    grid: '#2D4B7E',
    bg: '#FFFFFF',
    bgSoft: '#F1F5F9',
    docFill: '#FFFFFF',
    docFillEnd: '#F8FAFC',
    chartBg: '#F1F5F9',
    textLight: '#94A3B8',
    flame: '#F59E0B',
});
</script>

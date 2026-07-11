<?php

return [
    /*
     * Use a <script data-page> element for the initial page payload
     * (required by @inertiajs/vue3 v2+ which no longer reads <div data-page>).
     */
    'use_script_element_for_initial_page' => true,

    /*
     * SSR settings — disabled by default. Enable if you set up @inertiajs/vue3/server.
     */
    'ssr' => [
        'enabled' => false,
    ],
];

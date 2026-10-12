<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Masuk ke Panel Admin - ShopDress</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <style>
        @layer base {
            html, body { margin: 0; padding: 0; }
            body { overscroll-behavior: none; }
            main > :first-child { margin-top: 0 !important; }
            main > :last-child { margin-bottom: 0 !important; }
        }
        ::-webkit-scrollbar { display: none; }
        [x-cloak] { display: none !important; }
    </style>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-error-container": "#93000a",
                        "primary-600": "#6D28D9",
                        "surface-white": "#FFFFFF",
                        "error": "#ba1a1a",
                        "tertiary": "#004b31",
                        "canvas-bg": "#F8FAFC",
                        "outline-variant": "#ccc3d7",
                        "on-secondary-fixed-variant": "#3a485b",
                        "tertiary-fixed": "#6ffbbe",
                        "badge-warning-bg": "#FFFBEB",
                        "surface-container-high": "#e2e7ff",
                        "surface-bright": "#faf8ff",
                        "badge-info-border": "#BFDBFE",
                        "on-surface": "#131b2e",
                        "on-secondary-container": "#57657a",
                        "on-error": "#ffffff",
                        "on-primary-fixed-variant": "#5b00c5",
                        "surface-dim": "#d2d9f4",
                        "secondary-container": "#d5e3fc",
                        "on-tertiary-fixed-variant": "#005236",
                        "inverse-on-surface": "#eef0ff",
                        "on-primary-container": "#dac5ff",
                        "surface-container": "#eaedff",
                        "primary-950": "#2E1065",
                        "badge-danger-bg": "#FEF2F2",
                        "badge-warning-text": "#92400E",
                        "badge-success-border": "#A7F3D0",
                        "outline": "#7b7486",
                        "on-primary-fixed": "#250059",
                        "surface": "#faf8ff",
                        "secondary": "#515f74",
                        "badge-info-bg": "#EFF6FF",
                        "inverse-surface": "#283044",
                        "on-background": "#131b2e",
                        "primary-fixed": "#ebddff",
                        "primary-700": "#5B21B6",
                        "on-secondary": "#ffffff",
                        "surface-tint": "#7331df",
                        "tertiary-fixed-dim": "#4edea3",
                        "on-secondary-fixed": "#0d1c2e",
                        "badge-neutral-text": "#334155",
                        "on-tertiary-fixed": "#002113",
                        "secondary-fixed": "#d5e3fc",
                        "surface-container-highest": "#dae2fd",
                        "badge-danger-border": "#FECACA",
                        "inverse-primary": "#d3bbff",
                        "on-primary": "#ffffff",
                        "primary-200": "#DDD6FE",
                        "badge-info-text": "#1E40AF",
                        "secondary-fixed-dim": "#b9c7df",
                        "surface-container-lowest": "#ffffff",
                        "on-surface-variant": "#4a4455",
                        "error-container": "#ffdad6",
                        "badge-neutral-bg": "#F1F5F9",
                        "surface-variant": "#dae2fd",
                        "background": "#faf8ff",
                        "primary": "#5300b7",
                        "primary-500": "#7C3AED",
                        "primary-50": "#F5F3FF",
                        "on-tertiary-container": "#58e7ab",
                        "on-tertiary": "#ffffff",
                        "badge-danger-text": "#991B1B",
                        "badge-neutral-border": "#CBD5E1",
                        "border-subtle": "#E2E8F0",
                        "badge-warning-border": "#FDE68A",
                        "badge-success-text": "#065F46",
                        "tertiary-container": "#006544",
                        "primary-container": "#6d28d9",
                        "surface-container-low": "#f2f3ff",
                        "text-muted": "#64748B",
                        "border-strong": "#CBD5E1",
                        "primary-100": "#EDE9FE",
                        "primary-fixed-dim": "#d3bbff",
                        "badge-success-bg": "#ECFDF5"
                    },
                    borderRadius: {
                        DEFAULT: "0.25rem",
                        lg: "0.5rem",
                        xl: "0.75rem",
                        full: "9999px"
                    },
                    spacing: {
                        "space-xl": "2rem",
                        gutter: "1.5rem",
                        "space-md": "1rem",
                        margin: "2rem",
                        "space-xs": "0.25rem",
                        "space-lg": "1.5rem",
                        "space-sm": "0.5rem"
                    },
                    fontFamily: {
                        "section-title": ["Inter"],
                        "badge-label": ["Inter"],
                        caption: ["Inter"],
                        "body-sm": ["Inter"],
                        "table-header": ["Inter"],
                        "body-bold": ["Inter"],
                        "body-medium": ["Inter"],
                        "currency-figure": ["Inter"],
                        "body-default": ["Inter"],
                        "metric-stat": ["Inter"],
                        "page-title": ["Inter"]
                    },
                    fontSize: {
                        "section-title": ["18px", { lineHeight: "24px", letterSpacing: "-0.01em", fontWeight: "600" }],
                        "badge-label": ["12px", { lineHeight: "16px", letterSpacing: "0.01em", fontWeight: "600" }],
                        caption: ["12px", { lineHeight: "16px", fontWeight: "500" }],
                        "body-sm": ["13px", { lineHeight: "18px", fontWeight: "400" }],
                        "table-header": ["12px", { lineHeight: "16px", letterSpacing: "0.05em", fontWeight: "600" }],
                        "body-bold": ["14px", { lineHeight: "20px", fontWeight: "600" }],
                        "body-medium": ["14px", { lineHeight: "20px", fontWeight: "500" }],
                        "currency-figure": ["14px", { lineHeight: "20px", letterSpacing: "-0.01em", fontWeight: "600" }],
                        "body-default": ["14px", { lineHeight: "20px", fontWeight: "400" }],
                        "metric-stat": ["30px", { lineHeight: "36px", letterSpacing: "-0.025em", fontWeight: "700" }],
                        "page-title": ["24px", { lineHeight: "32px", letterSpacing: "-0.02em", fontWeight: "700" }]
                    }
                }
            }
        };
    </script>
    
    @livewireStyles
</head>
<body class="m-0 p-0 antialiased">
    {{ $slot }}

    @livewireScripts
</body>
</html>

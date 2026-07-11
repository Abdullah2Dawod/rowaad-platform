@php
    use App\Models\SiteSetting;
    $siteNameAr    = SiteSetting::get('site.name_ar', 'رواد بلا حدود');
    $favicon       = SiteSetting::get('site.favicon', '/images/rowaad-logo-symbol.png');
    $favicon       = str_starts_with($favicon, 'http') || str_starts_with($favicon, '/') ? $favicon : '/storage/'.ltrim($favicon,'/');
    $seoTitle      = SiteSetting::get('marketing.seo_title', $siteNameAr.' — استشارات اقتصادية');
    $seoDesc       = SiteSetting::get('marketing.seo_description');
    $seoKeywords   = SiteSetting::get('marketing.seo_keywords');
    $gtmId         = SiteSetting::get('marketing.gtm_id');
    $ga4Id         = SiteSetting::get('marketing.ga4_id');
    $metaPixel     = SiteSetting::get('marketing.meta_pixel');
    $tiktokPixel   = SiteSetting::get('marketing.tiktok_pixel');
    $snapPixel     = SiteSetting::get('marketing.snap_pixel');
    $hotjarId      = SiteSetting::get('marketing.hotjar_id');
    $tawkId        = SiteSetting::get('marketing.tawk_id');
    $logoLight     = SiteSetting::get('site.logo', '/images/rowaad-logo-symbol.png');
    $logoLight     = str_starts_with($logoLight, 'http') || str_starts_with($logoLight, '/') ? $logoLight : '/storage/'.ltrim($logoLight,'/');
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title inertia>{{ $seoTitle }}</title>

    {{-- ─── SEO (from admin marketing settings) ─── --}}
    @if($seoDesc)      <meta name="description" content="{{ $seoDesc }}"> @endif
    @if($seoKeywords)  <meta name="keywords" content="{{ $seoKeywords }}"> @endif
    <meta property="og:title"       content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDesc }}">
    <meta property="og:image"       content="{{ asset($logoLight) }}">
    <meta property="og:type"        content="website">
    <meta name="twitter:card"       content="summary_large_image">

    {{-- ─── Favicon (admin-uploaded) ─── --}}
    <link rel="icon"          type="image/png" href="{{ $favicon }}">
    <link rel="shortcut icon" type="image/png" href="{{ $favicon }}">
    <link rel="apple-touch-icon"                href="{{ $favicon }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@200;300;400;500;600;700;800;900&family=Almarai:wght@300;400;700;800&display=swap" rel="stylesheet" />

    @routes
    @vite('resources/js/app.js')
    @inertiaHead

    {{-- ═══════════════════════════════════════════════════════════
         MARKETING PIXELS — driven by admin Settings page
         Each block only renders when the corresponding ID is set.
         ═══════════════════════════════════════════════════════════ --}}

    @if($gtmId)
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{{ $gtmId }}');
        </script>
    @endif

    @if($ga4Id)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4Id }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $ga4Id }}');
        </script>
    @endif

    @if($metaPixel)
        <script>
            !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $metaPixel }}');
            fbq('track', 'PageView');
        </script>
    @endif

    @if($tiktokPixel)
        <script>
            !function (w, d, t) { w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"];ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e};ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};
            ttq.load('{{ $tiktokPixel }}');
            ttq.page();
            }(window, document, 'ttq');
        </script>
    @endif

    @if($snapPixel)
        <script>
            (function(e,t,n){if(e.snaptr)return;var a=e.snaptr=function(){a.handleRequest?a.handleRequest.apply(a,arguments):a.queue.push(arguments)};a.queue=[];var s='script',r=t.createElement(s);r.async=!0;r.src=n;var u=t.getElementsByTagName(s)[0];u.parentNode.insertBefore(r,u);})(window,document,'https://sc-static.net/scevent.min.js');
            snaptr('init', '{{ $snapPixel }}');
            snaptr('track', 'PAGE_VIEW');
        </script>
    @endif

    @if($hotjarId)
        <script>
            (function(h,o,t,j,a,r){h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};h._hjSettings={hjid:{{ (int) $hotjarId }},hjsv:6};a=o.getElementsByTagName('head')[0];r=o.createElement('script');r.async=1;r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;a.appendChild(r);})(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
        </script>
    @endif

    @if($tawkId)
        <script>
            var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
            (function(){var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];s1.async=true;s1.src='https://embed.tawk.to/{{ $tawkId }}';s1.charset='UTF-8';s1.setAttribute('crossorigin','*');s0.parentNode.insertBefore(s1,s0);})();
        </script>
    @endif
</head>
<body class="antialiased">
    @if($gtmId)
        <noscript>
            <iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}" height="0" width="0" style="display:none;visibility:hidden"></iframe>
        </noscript>
    @endif
    @inertia
</body>
</html>

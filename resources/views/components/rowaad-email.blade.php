<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'رواد بلا حدود' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@400;600;700;800&family=Almarai:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; padding: 0; background: #F0F4FA; font-family: 'Almarai', 'Segoe UI', 'Tahoma', sans-serif; direction: rtl; color: #1A2F50; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table { border-collapse: collapse; mso-table-lspace: 0; mso-table-rspace: 0; }
        img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
        .wrap { width: 100%; padding: 32px 16px; background: linear-gradient(180deg, #F0F4FA 0%, #E5F1F2 100%); }
        .card { max-width: 620px; margin: 0 auto; background: #FFFFFF; border-radius: 20px; overflow: hidden; box-shadow: 0 12px 40px -8px rgba(45, 75, 126, 0.15); }
        .header { padding: 36px 40px 32px; background: linear-gradient(135deg, #0A1729 0%, #122440 55%, #1A2F50 100%); text-align: center; position: relative; }
        .header-bar { height: 4px; background: linear-gradient(90deg, #2D4B7E 0%, #3DAFB9 50%, #6BC8D2 100%); }
        .brand-name { font-family: 'Alexandria', sans-serif; font-size: 22px; font-weight: 800; color: #FFFFFF; margin: 0; letter-spacing: -0.01em; }
        .brand-tagline { font-size: 11px; color: #6BC8D2; margin-top: 6px; letter-spacing: 0.2em; text-transform: uppercase; }
        .eyebrow { display: inline-block; padding: 6px 14px; background: rgba(61, 175, 185, 0.10); border: 1px solid rgba(61, 175, 185, 0.30); border-radius: 999px; color: #3DAFB9; font-size: 11px; font-weight: 800; letter-spacing: 0.15em; margin-bottom: 16px; }
        .body { padding: 40px; color: #1A2F50; font-size: 15px; line-height: 1.85; }
        .body h1 { font-family: 'Alexandria', sans-serif; font-size: 26px; font-weight: 800; color: #2D4B7E; margin: 0 0 8px; line-height: 1.25; letter-spacing: -0.015em; }
        .body p { margin: 0 0 16px; color: #475569; }
        .body strong { color: #1A2F50; font-weight: 700; }
        .panel { margin: 20px 0; padding: 20px 22px; background: linear-gradient(135deg, rgba(61, 175, 185, 0.06), rgba(45, 75, 126, 0.04)); border: 1px solid rgba(61, 175, 185, 0.18); border-radius: 14px; }
        .panel-title { font-size: 11px; font-weight: 800; color: #3DAFB9; letter-spacing: 0.2em; text-transform: uppercase; margin: 0 0 10px; }
        .kv-row { display: block; padding: 8px 0; border-bottom: 1px dashed rgba(148, 163, 184, 0.25); }
        .kv-row:last-child { border-bottom: none; }
        .kv-key { color: #64748B; font-size: 13px; font-weight: 600; }
        .kv-val { color: #1A2F50; font-size: 14px; font-weight: 700; float: left; }
        .btn-wrap { text-align: center; margin: 28px 0 8px; }
        .btn { display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #2D4B7E 0%, #3DAFB9 100%); color: #FFFFFF !important; font-size: 14px; font-weight: 800; text-decoration: none; border-radius: 999px; box-shadow: 0 10px 24px -8px rgba(61, 175, 185, 0.55); letter-spacing: -0.005em; }
        .btn-secondary { background: transparent; color: #3DAFB9 !important; border: 1.5px solid #3DAFB9; box-shadow: none; }
        .highlight { margin: 20px 0; padding: 18px 22px; background: linear-gradient(135deg, #0A1729 0%, #1A2F50 100%); border-radius: 14px; color: #FFFFFF; }
        .highlight-label { font-size: 10.5px; font-weight: 800; color: #6BC8D2; letter-spacing: 0.2em; text-transform: uppercase; margin: 0 0 6px; }
        .highlight code { display: inline-block; padding: 4px 10px; background: rgba(107, 200, 210, 0.15); border: 1px solid rgba(107, 200, 210, 0.3); border-radius: 8px; font-family: 'Courier New', monospace; font-size: 15px; font-weight: 700; color: #FFFFFF; direction: ltr; }
        .divider { height: 1px; background: linear-gradient(90deg, transparent, rgba(148, 163, 184, 0.3), transparent); margin: 28px 0; border: none; }
        .footer { padding: 28px 40px; background: #F8FAFC; border-top: 1px solid rgba(148, 163, 184, 0.15); text-align: center; color: #64748B; font-size: 12px; }
        .footer-brand { color: #2D4B7E; font-weight: 700; margin-bottom: 4px; }
        .footer a { color: #3DAFB9; text-decoration: none; font-weight: 600; }
        .unsub { display: block; margin-top: 12px; font-size: 11px; color: #94A3B8; }
        @media only screen and (max-width: 620px) {
            .wrap { padding: 16px 8px; } .header { padding: 28px 24px 24px; } .body { padding: 28px 24px; } .footer { padding: 20px 24px; } .body h1 { font-size: 22px; }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <table role="presentation" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td align="center">
                    <div class="card">
                        <div class="header-bar"></div>
                        <div class="header">
                            <p class="brand-name">رواد بلا حدود</p>
                            <p class="brand-tagline">للاستشارات الاقتصادية</p>
                        </div>
                        <div class="body">
                            {{ $slot }}
                        </div>
                        <div class="footer">
                            <div class="footer-brand">رواد بلا حدود · الرياض، المملكة العربية السعودية</div>
                            <div>
                                <a href="mailto:info@rowaad.org">info@rowaad.org</a>
                                &nbsp;·&nbsp;
                                <a href="{{ url('/') }}">rowaad.org</a>
                            </div>
                            @if(isset($unsubscribeUrl))
                                <a href="{{ $unsubscribeUrl }}" class="unsub">إلغاء الاشتراك من هذه النشرات</a>
                            @endif
                            <div class="unsub">
                                © {{ date('Y') }} رواد بلا حدود. جميع الحقوق محفوظة.
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>

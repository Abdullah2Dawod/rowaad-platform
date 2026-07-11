@php
    $small = $small ?? false;
    $dark  = $dark ?? false;
    $bg    = $bg ?? '#F8FAFC';
    $box   = $small ? 64 : 96;
@endphp
<div style="margin-bottom:10px;">
    <div style="font-size:10.5px; font-weight:800; color:#3DAFB9; letter-spacing:0.12em; text-transform:uppercase; margin-bottom:8px;">
        {{ $label }}
    </div>
    <div style="
        display:flex; align-items:center; justify-content:center;
        width:100%; min-height:{{ $box + 40 }}px; padding:16px;
        background:{{ $bg }};
        border:1px dashed rgba(61,175,185,.25);
        border-radius:14px;
        {{ $dark ? 'color:#fff;' : 'color:#0F172A;' }}
    ">
        @if($url)
            <img src="{{ $url }}" alt="{{ $label }}"
                 style="max-width:100%; max-height:{{ $box }}px; object-fit:contain; display:block;"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';" />
            <div style="display:none; flex-direction:column; align-items:center; gap:6px; opacity:.5;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="28" height="28">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span style="font-size:11px; font-weight:600;">تعذّر تحميل الصورة</span>
            </div>
        @else
            <div style="display:flex; flex-direction:column; align-items:center; gap:6px; opacity:.4;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="28" height="28">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span style="font-size:11px; font-weight:700;">لم تُرفَع صورة بعد</span>
            </div>
        @endif
    </div>
</div>

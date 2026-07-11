{{-- Compact, elegant circular avatar preview for the admin Settings page. --}}
<div class="rowaad-avatar-card" style="
    display:flex; align-items:center; gap:14px;
    padding:14px 16px;
    background: linear-gradient(135deg, rgba(61,175,185,0.05), rgba(45,75,126,0.02));
    border:1px solid rgba(61,175,185,0.18);
    border-radius:16px;
    max-width: 100%;
">
    <div style="position:relative; flex-shrink:0;">
        @if($url)
            <img
                src="{{ $url }}"
                alt="{{ $name }}"
                style="
                    width:64px; height:64px;
                    border-radius:9999px;
                    object-fit:cover;
                    box-shadow: 0 6px 18px -4px rgba(45,75,126,0.35);
                    border: 2px solid #fff;
                    display:block;
                "
                onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"
            />
            <div
                style="
                    width:64px; height:64px;
                    border-radius:9999px;
                    display:none; align-items:center; justify-content:center;
                    color:#fff; font-weight:900; font-size:20px;
                    background: linear-gradient(135deg, #2D4B7E 0%, #3DAFB9 100%);
                    box-shadow: 0 6px 18px -4px rgba(61,175,185,0.4);
                    position:absolute; inset:0;
                ">
                {{ $initials ?: 'A' }}
            </div>
        @else
            <div style="
                width:64px; height:64px;
                border-radius:9999px;
                display:flex; align-items:center; justify-content:center;
                color:#fff; font-weight:900; font-size:20px;
                background: linear-gradient(135deg, #2D4B7E 0%, #3DAFB9 100%);
                box-shadow: 0 6px 18px -4px rgba(61,175,185,0.4);
                border: 2px solid #fff;
            ">
                {{ $initials ?: 'A' }}
            </div>
        @endif
        {{-- Small camera badge --}}
        <div style="
            position:absolute; bottom:-2px; left:-2px;
            width:22px; height:22px; border-radius:9999px;
            display:flex; align-items:center; justify-content:center;
            background: linear-gradient(135deg, #3DAFB9 0%, #2D4B7E 100%);
            box-shadow: 0 2px 6px rgba(0,0,0,0.25);
            border: 2px solid #fff;
        ">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px;color:#fff;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
    </div>

    <div style="flex:1; min-width:0;">
        <div style="font-size:10px; font-weight:800; letter-spacing:0.18em; text-transform:uppercase; color:#3DAFB9;">
            الصورة الحالية
        </div>
        <div style="font-size:14px; font-weight:800; color: rgb(var(--gray-900)); margin-top:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
            {{ $name }}
        </div>
        <div style="font-size:11px; color: rgb(var(--gray-500)); margin-top:3px;">
            @if($url) استبدل عبر "اختر صورة" أو احذف بالزر أدناه. @else لم تُرفع صورة بعد. @endif
        </div>
    </div>
</div>

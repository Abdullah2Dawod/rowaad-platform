@php
    $labels = [
        'professional_title'        => 'المسمى المهني',
        'bio_ar'                    => 'النبذة (عربي)',
        'bio_en'                    => 'النبذة (إنجليزي)',
        'specialization_id'         => 'التخصص الأساسي',
        'secondary_specializations' => 'التخصصات الفرعية',
        'years_experience'          => 'سنوات الخبرة',
        'hourly_rate'               => 'السعر بالساعة (ر.س)',
        'session_duration_min'      => 'مدة الجلسة (دقيقة)',
        'services'                  => 'الخدمات المُقدَّمة',
        'certificates'              => 'الشهادات',
        'linkedin_url'              => 'LinkedIn',
        'website_url'               => 'الموقع الإلكتروني',
    ];

    $renderVal = function ($v) {
        if ($v === null || $v === '') return '—';
        if (is_bool($v)) return $v ? 'نعم' : 'لا';
        if (is_array($v)) {
            $items = array_map(fn ($x) => is_array($x) ? json_encode($x, JSON_UNESCAPED_UNICODE) : (string) $x, $v);
            return implode('، ', array_filter($items, fn ($s) => $s !== ''));
        }
        return (string) $v;
    };

    // Resolve specialization label if the field changed
    $specName = fn ($id) => $id ? optional(\App\Models\Specialization::find($id))->name_ar : null;
@endphp

<div style="direction:rtl;">
    {{-- Header info banner --}}
    <div style="
        padding: 14px 16px; margin-bottom: 16px;
        background: linear-gradient(135deg, rgba(245,158,11,0.08), rgba(217,119,6,0.03));
        border: 1px solid rgba(245,158,11,0.25);
        border-radius: 12px;
        display:flex; gap:12px; align-items:flex-start;">
        <div style="flex-shrink:0; width:36px; height:36px; border-radius:9999px; display:flex; align-items:center; justify-content:center;
                    background: linear-gradient(135deg,#F59E0B,#D97706); color:#fff;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div style="flex:1;">
            <div style="font-size:14px; font-weight:900; color:#92400E;">تعديلات بحاجة لاعتمادك</div>
            <div style="font-size:12.5px; color:#78350F; margin-top:2px; line-height:1.7;">
                قدّم المستشار <strong>{{ $consultant->full_name_ar ?: $consultant->user?->name }}</strong>
                طلباً لتعديل <strong>{{ count($changes) }}</strong> حقلاً حسّاساً.
                راجع القيم الحالية والجديدة، ثم اعتمد أو ارفض.
            </div>
            <div style="font-size:11px; color:#9A6712; margin-top:4px;">
                وقت التقديم: {{ $consultant->pending_changes_submitted_at?->diffForHumans() ?? '—' }}
            </div>
        </div>
    </div>

    {{-- Comparison table --}}
    <div style="border:1px solid #E5E7EB; border-radius:14px; overflow:hidden; background:#fff;">
        <table style="width:100%; border-collapse:collapse; font-size:13.5px;">
            <thead>
                <tr style="background:#F8FAFC;">
                    <th style="text-align:right; padding:12px 14px; font-size:11px; font-weight:800; color:#64748B; letter-spacing:0.15em; text-transform:uppercase; border-bottom:1px solid #E5E7EB; width:26%;">
                        الحقل
                    </th>
                    <th style="text-align:right; padding:12px 14px; font-size:11px; font-weight:800; color:#64748B; letter-spacing:0.15em; text-transform:uppercase; border-bottom:1px solid #E5E7EB;">
                        القيمة الحالية
                    </th>
                    <th style="text-align:right; padding:12px 14px; font-size:11px; font-weight:800; color:#059669; letter-spacing:0.15em; text-transform:uppercase; border-bottom:1px solid #E5E7EB;">
                        القيمة الجديدة
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($changes as $key => $newValue)
                    @php
                        $label = $labels[$key] ?? $key;
                        $currentValue = $consultant->{$key} ?? null;
                        $renderedCurrent = $renderVal($currentValue);
                        $renderedNew     = $renderVal($newValue);
                        if ($key === 'specialization_id') {
                            $renderedCurrent = $specName($currentValue) ?: '—';
                            $renderedNew     = $specName($newValue) ?: '—';
                        }
                        if (in_array($key, ['hourly_rate']) && is_numeric($newValue)) {
                            $renderedNew = number_format((float) $newValue, 2) . ' ر.س';
                        }
                        if (in_array($key, ['hourly_rate']) && is_numeric($currentValue)) {
                            $renderedCurrent = number_format((float) $currentValue, 2) . ' ر.س';
                        }
                    @endphp
                    <tr style="border-bottom:1px solid #F1F5F9;">
                        <td style="padding:14px; vertical-align:top;">
                            <div style="font-weight:800; color:#1E293B; font-size:13px;">{{ $label }}</div>
                            <div style="font-family:'Courier New',monospace; font-size:10px; color:#94A3B8; margin-top:2px;">{{ $key }}</div>
                        </td>
                        <td style="padding:14px; vertical-align:top; color:#64748B;">
                            <div style="background:#F8FAFC; padding:8px 10px; border-radius:8px; text-decoration:line-through; text-decoration-color:#CBD5E1; word-break:break-word;">
                                {{ $renderedCurrent }}
                            </div>
                        </td>
                        <td style="padding:14px; vertical-align:top;">
                            <div style="background:#ECFDF5; border:1px solid #A7F3D0; padding:8px 10px; border-radius:8px; color:#065F46; font-weight:700; word-break:break-word;">
                                {{ $renderedNew }}
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px; padding:10px 14px; background:#F0F9FF; border:1px solid #BAE6FD; border-radius:10px; font-size:12px; color:#0C4A6E;">
        <strong>ملاحظة:</strong> اعتماد التعديلات يُطبّقها فوراً على الملف العام للمستشار،
        والرفض يُلغيها ولا يُخطر المستشار تلقائياً — يُنصح إبلاغه بسبب الرفض يدوياً.
    </div>
</div>

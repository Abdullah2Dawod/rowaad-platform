<x-filament-panels::page>
    @php $fmt = fn ($v) => number_format((float) $v, 2); @endphp

    {{-- KPI row --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Available balance --}}
        <div class="rounded-2xl p-6 text-white shadow-lg"
             style="background: linear-gradient(135deg, #0A1729 0%, #1A2F50 55%, #2D4B7E 100%);">
            <div class="text-[11px] font-bold tracking-[0.2em] uppercase" style="color:#6BC8D2;">الرصيد المتاح للسحب</div>
            <div class="mt-3 text-4xl font-black">{{ $fmt($available) }} <span class="text-base font-normal opacity-80">{{ $currency }}</span></div>
            <div class="mt-2 text-xs opacity-80">
                @if($available >= $minWithdrawal)
                    جاهز للسحب — اضغط "طلب سحب" في الأعلى
                @else
                    الحد الأدنى للسحب {{ $fmt($minWithdrawal) }} {{ $currency }}
                @endif
            </div>
        </div>

        {{-- Pending --}}
        <div class="rounded-2xl p-6 border border-amber-200 bg-amber-50 dark:bg-amber-950/30 dark:border-amber-800/40">
            <div class="text-[11px] font-bold tracking-[0.2em] uppercase text-amber-600 dark:text-amber-400">قيد التنفيذ</div>
            <div class="mt-3 text-4xl font-black text-amber-700 dark:text-amber-300">{{ $fmt($pending) }} <span class="text-base font-normal">{{ $currency }}</span></div>
            <div class="mt-2 text-xs text-amber-700/80 dark:text-amber-400/80">حجوزات مؤكّدة/مدفوعة تنتظر الإكمال</div>
        </div>

        {{-- Lifetime --}}
        <div class="rounded-2xl p-6 border" style="border-color: rgba(61,175,185,0.3); background: linear-gradient(135deg, rgba(61,175,185,0.08), rgba(45,75,126,0.04));">
            <div class="text-[11px] font-bold tracking-[0.2em] uppercase" style="color:#3DAFB9;">إجمالي الأرباح</div>
            <div class="mt-3 text-4xl font-black" style="color:#2D4B7E;">{{ $fmt($lifetime) }} <span class="text-base font-normal">{{ $currency }}</span></div>
            <div class="mt-2 text-xs text-slate-500">مجموع أرباحك منذ الانضمام</div>
        </div>
    </div>

    {{-- Monthly summary --}}
    <div class="grid grid-cols-2 gap-4 mt-4">
        <div class="rounded-xl p-4 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800">
            <div class="text-[10.5px] text-slate-500 font-bold tracking-widest uppercase">هذا الشهر</div>
            <div class="text-2xl font-black mt-1" style="color:#2D4B7E;">{{ $fmt($thisMonth) }} <span class="text-sm font-normal">{{ $currency }}</span></div>
        </div>
        <div class="rounded-xl p-4 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800">
            <div class="text-[10.5px] text-slate-500 font-bold tracking-widest uppercase">الشهر الماضي</div>
            <div class="text-2xl font-black mt-1 text-slate-700 dark:text-slate-200">{{ $fmt($lastMonth) }} <span class="text-sm font-normal">{{ $currency }}</span></div>
        </div>
    </div>

    {{-- Distribution info --}}
    <div class="mt-6 rounded-2xl p-5 border" style="border-color: rgba(61,175,185,0.2); background: rgba(61,175,185,0.05);">
        <div class="flex items-start gap-3">
            <x-heroicon-o-information-circle class="w-5 h-5 shrink-0 mt-0.5" style="color:#3DAFB9;"/>
            <div class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
                <div class="font-bold mb-1" style="color:#2D4B7E;">آلية توزيع الأرباح</div>
                من كل حجز يدفعه العميل: <strong>50%</strong> يعود لك كاستشاري، <strong>50%</strong> حصة المنصة،
                و<strong>15%</strong> ضريبة زكاة تُضاف على المبلغ الأصلي ويدفعها العميل ولا تُخصم منك.
            </div>
        </div>
    </div>

    {{-- Withdrawal requests history --}}
    <div class="mt-6 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
            <div class="font-black text-slate-900 dark:text-slate-100">طلبات السحب</div>
            <div class="text-xs text-slate-500">{{ $withdrawals->count() }} طلب</div>
        </div>
        @if($withdrawals->isEmpty())
            <div class="p-8 text-center text-slate-500">
                <x-heroicon-o-banknotes class="w-10 h-10 mx-auto opacity-40"/>
                <div class="mt-2 text-sm">لم تقدّم أي طلبات سحب بعد.</div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 text-[11px] uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="text-right px-5 py-3">المرجع</th>
                            <th class="text-right px-5 py-3">التاريخ</th>
                            <th class="text-right px-5 py-3">المبلغ</th>
                            <th class="text-right px-5 py-3">البنك</th>
                            <th class="text-right px-5 py-3">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($withdrawals as $w)
                            <tr>
                                <td class="px-5 py-3 font-mono text-xs" dir="ltr">{{ $w->reference }}</td>
                                <td class="px-5 py-3 text-xs text-slate-500">{{ $w->created_at->format('Y-m-d H:i') }}</td>
                                <td class="px-5 py-3 font-black" style="color:#2D4B7E;">{{ $fmt($w->amount) }}</td>
                                <td class="px-5 py-3 text-slate-600 dark:text-slate-400">{{ $w->bank_name }}</td>
                                <td class="px-5 py-3">
                                    @php
                                        $c = match($w->status){
                                            'pending'  => 'bg-amber-100 text-amber-700',
                                            'approved' => 'bg-blue-100 text-blue-700',
                                            'paid'     => 'bg-emerald-100 text-emerald-700',
                                            'rejected' => 'bg-red-100 text-red-700',
                                            default    => 'bg-slate-100',
                                        };
                                        $l = match($w->status){
                                            'pending'  => 'قيد المراجعة',
                                            'approved' => 'مُعتمد — قيد التحويل',
                                            'paid'     => 'تم التحويل',
                                            'rejected' => 'مرفوض',
                                            default    => $w->status,
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-[10.5px] font-bold {{ $c }}">{{ $l }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Earnings transactions --}}
    <div class="mt-6 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
            <div class="font-black text-slate-900 dark:text-slate-100">آخر الحجوزات المُربحة</div>
            <div class="text-xs text-slate-500">{{ $transactions->count() }} حجز</div>
        </div>

        @if($transactions->isEmpty())
            <div class="p-10 text-center text-slate-500">
                <x-heroicon-o-inbox class="w-12 h-12 mx-auto opacity-40"/>
                <div class="mt-3 text-sm">لا توجد أرباح بعد — ستظهر هنا فور اكتمال أول حجز.</div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 text-[11px] uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="text-right px-5 py-3">المرجع</th>
                            <th class="text-right px-5 py-3">العميل</th>
                            <th class="text-right px-5 py-3">تاريخ الدفع</th>
                            <th class="text-right px-5 py-3">الأساسي</th>
                            <th class="text-right px-5 py-3">حصتك</th>
                            <th class="text-right px-5 py-3">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($transactions as $b)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30">
                                <td class="px-5 py-3 font-mono text-xs" dir="ltr">{{ $b->reference }}</td>
                                <td class="px-5 py-3">{{ $b->user?->name ?? '—' }}</td>
                                <td class="px-5 py-3 text-slate-600 dark:text-slate-400 text-xs">{{ $b->paid_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                <td class="px-5 py-3 text-slate-500">{{ $fmt($b->consultant_share + $b->platform_share) }}</td>
                                <td class="px-5 py-3 font-black" style="color:#059669;">+ {{ $fmt($b->consultant_share) }}</td>
                                <td class="px-5 py-3">
                                    @php
                                        $c = match($b->status){
                                            'paid'      => 'bg-blue-100 text-blue-700',
                                            'confirmed' => 'bg-amber-100 text-amber-700',
                                            'completed' => 'bg-emerald-100 text-emerald-700',
                                            default     => 'bg-slate-100',
                                        };
                                        $l = match($b->status){
                                            'paid'      => 'مدفوع',
                                            'confirmed' => 'مؤكّد',
                                            'completed' => 'مكتمل',
                                            default     => $b->status,
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10.5px] font-bold {{ $c }}">{{ $l }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-filament-panels::page>

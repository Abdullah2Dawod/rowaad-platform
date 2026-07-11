<template>
    <Head title="شروط الاستخدام" />
    <LegalShell
        eyebrow="الشروط · الوثائق القانونية"
        last-updated="2026-07-04"
        :toc="toc"
        other-label="سياسة الخصوصية"
        other-hint="كيف نجمع بياناتك ونحميها"
        other-href="/privacy"
        other-cta="اقرأ سياسة الخصوصية"
    >
        <template #title>شروط استخدام المنصة</template>
        <template #subtitle>
            شروط الاستخدام في <span class="font-black text-ink">رواد بلا حدود</span> بمثابة دستور تسير عليه للحصول على الخدمات.
            نرجو منك قراءتها بعناية قبل استخدام أي من خدمات المنصة، فباستمرارك في استخدامها فأنت توافق على هذه الشروط بالكامل.
        </template>

        <!-- Intro -->
        <div class="mb-10 p-5 rounded-2xl bg-gradient-to-br from-[#3DAFB9]/8 to-[#2D4B7E]/5 border border-[#3DAFB9]/20">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#3DAFB9]/15 border border-[#3DAFB9]/25 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <p class="text-[13.5px] text-ink-body leading-[1.9]">
                    هذه الشروط تُنظّم علاقتك بمنصة رواد بلا حدود للاستشارات الاقتصادية. يحقّ لنا
                    <span class="font-bold text-ink">تحديث هذه الشروط في أي وقت</span>، وسنُبلغك بأي تغييرات جوهرية عبر الوسائل المعتمدة.
                </p>
            </div>
        </div>

        <!-- Section: About services -->
        <section id="services" class="mb-12 scroll-mt-32">
            <h2 class="text-2xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-4 flex items-center gap-3">
                <span class="w-1 h-7 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                نطاق الخدمات
            </h2>
            <p class="text-[14px] text-ink-body leading-[1.9] mb-4">
                يُقدّم مكتب رواد مجموعة شاملة من خدمات الاستشارات الاقتصادية، تُغطّي الجوانب الاقتصادية الكلية والجزئية
                على حدٍّ سواء، بما في ذلك:
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div v-for="(s, i) in services" :key="i" class="p-4 rounded-xl border border-soft hover:border-[#3DAFB9]/40 transition-colors">
                    <div class="flex items-center gap-2.5 mb-1.5">
                        <span class="w-7 h-7 rounded-lg bg-gradient-to-br from-[#3DAFB9]/15 to-[#2D4B7E]/10 border border-[#3DAFB9]/25 flex items-center justify-center text-[11px] font-black text-[#3DAFB9]">{{ i + 1 }}</span>
                        <span class="text-[13.5px] font-black text-ink">{{ s.title }}</span>
                    </div>
                    <p class="text-[12.5px] text-ink-body leading-relaxed pr-9 rtl:pr-9">{{ s.desc }}</p>
                </div>
            </div>
        </section>

        <!-- Section: Account -->
        <section id="account" class="mb-12 scroll-mt-32">
            <h2 class="text-2xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-4 flex items-center gap-3">
                <span class="w-1 h-7 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                إنشاء الحساب واستخدامه
            </h2>
            <ul class="space-y-3">
                <li v-for="(a, i) in accountRules" :key="i" class="flex items-start gap-3 p-3 rounded-xl bg-canvas">
                    <span class="w-6 h-6 rounded-lg bg-[#3DAFB9]/12 text-[#3DAFB9] flex items-center justify-center text-[11px] font-black shrink-0 mt-0.5">{{ i + 1 }}</span>
                    <span class="text-[13px] text-ink-body leading-relaxed flex-1">{{ a }}</span>
                </li>
            </ul>
        </section>

        <!-- Section: Rights -->
        <section id="rights" class="mb-12 scroll-mt-32">
            <h2 class="text-2xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-4 flex items-center gap-3">
                <span class="w-1 h-7 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                حقوق المستخدم
            </h2>
            <p class="text-[14px] text-ink-body leading-[1.9] mb-4">
                نحرص على وضوح ما يحقّ لك عند استخدام المنصة، وبالمقابل ما تلتزم به من قواعد:
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-5 rounded-2xl bg-green-500/5 border border-green-500/20">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-[13.5px] font-black text-ink">لك الحقّ في</span>
                    </div>
                    <ul class="space-y-2">
                        <li v-for="(r, i) in allowed" :key="i" class="text-[12.5px] text-ink-body leading-relaxed flex items-start gap-2">
                            <span class="text-green-600 mt-1">•</span>
                            <span>{{ r }}</span>
                        </li>
                    </ul>
                </div>
                <div class="p-5 rounded-2xl bg-red-500/5 border border-red-500/20">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span class="text-[13.5px] font-black text-ink">لا يجوز</span>
                    </div>
                    <ul class="space-y-2">
                        <li v-for="(r, i) in forbidden" :key="i" class="text-[12.5px] text-ink-body leading-relaxed flex items-start gap-2">
                            <span class="text-red-500 mt-1">•</span>
                            <span>{{ r }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Section: Bookings -->
        <section id="bookings" class="mb-12 scroll-mt-32">
            <h2 class="text-2xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-4 flex items-center gap-3">
                <span class="w-1 h-7 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                الحجوزات والمدفوعات
            </h2>
            <ul class="space-y-2.5">
                <li v-for="(b, i) in bookingRules" :key="i" class="flex items-start gap-3 text-[13.5px] text-ink-body leading-[1.9]">
                    <svg class="w-4 h-4 text-[#3DAFB9] shrink-0 mt-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ b }}</span>
                </li>
            </ul>
        </section>

        <!-- Section: Consultants -->
        <section id="consultants" class="mb-12 scroll-mt-32">
            <h2 class="text-2xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-4 flex items-center gap-3">
                <span class="w-1 h-7 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                شروط انضمام المستشارين
            </h2>
            <p class="text-[14px] text-ink-body leading-[1.9] mb-4">
                إذا رغبت في الانضمام كمستشار في منصتنا، فيتعيّن عليك الالتزام بما يلي:
            </p>
            <ul class="space-y-2.5">
                <li v-for="(c, i) in consultantRules" :key="i" class="flex items-start gap-3 p-3 rounded-xl bg-canvas">
                    <span class="w-6 h-6 rounded-lg bg-gradient-to-br from-[#3DAFB9]/15 to-[#2D4B7E]/10 border border-[#3DAFB9]/25 text-[#3DAFB9] flex items-center justify-center text-[11px] font-black shrink-0 mt-0.5">{{ i + 1 }}</span>
                    <span class="text-[13px] text-ink-body leading-relaxed">{{ c }}</span>
                </li>
            </ul>
        </section>

        <!-- Section: IP -->
        <section id="ip" class="mb-12 scroll-mt-32">
            <h2 class="text-2xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-4 flex items-center gap-3">
                <span class="w-1 h-7 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                الملكية الفكرية
            </h2>
            <div class="p-5 rounded-2xl bg-amber-500/8 border border-amber-500/25 mb-4">
                <p class="text-[13.5px] text-ink-body leading-[1.9]">
                    الدراسات والتقارير والمحتوى المتوفّر في المنصة هي <span class="font-bold text-ink">ملكيّة حصرية لرواد بلا حدود</span>،
                    ولا يحقّ للمستخدمين نسخها أو إعادة نشرها أو استخدامها لأغراض تجارية دون إذن كتابي مسبق.
                </p>
            </div>
            <p class="text-[13.5px] text-ink-body leading-[1.9]">
                يُتاح لك استخدام هذه الدراسات لأغراض شخصية أو داخل مؤسّستك فقط، وفقاً لما نصّت عليه رخصة الاستخدام
                المحدّدة عند الشراء.
            </p>
        </section>

        <!-- Section: Company duties -->
        <section id="duties" class="mb-12 scroll-mt-32">
            <h2 class="text-2xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-4 flex items-center gap-3">
                <span class="w-1 h-7 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                التزامات رواد
            </h2>
            <p class="text-[14px] text-ink-body leading-[1.9] mb-4">
                في المقابل، تلتزم منصة رواد أمامك بما يلي:
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div v-for="(d, i) in duties" :key="i" class="p-4 rounded-xl bg-canvas border border-soft">
                    <div class="flex items-center gap-2.5 mb-1.5">
                        <span class="w-7 h-7 rounded-lg bg-[#3DAFB9]/12 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M9 12l2 2 4-4"/></svg>
                        </span>
                        <span class="text-[13px] font-black text-ink">{{ d.title }}</span>
                    </div>
                    <p class="text-[12px] text-ink-body leading-relaxed">{{ d.desc }}</p>
                </div>
            </div>
        </section>

        <!-- Section: Changes -->
        <section id="changes" class="mb-12 scroll-mt-32">
            <h2 class="text-2xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-4 flex items-center gap-3">
                <span class="w-1 h-7 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                تغييرات على الشروط
            </h2>
            <p class="text-[14px] text-ink-body leading-[1.9]">
                يحقّ لرواد تعديل هذه الشروط في أي وقت لتتناسب مع التطوّرات القانونية أو الخدمية. سيتم إشعارك بأي تحديثات
                جوهرية، ويُعدّ استمرارك في استخدام المنصة موافقة على الشروط المحدّثة. ننصحك بمراجعة هذه الصفحة بشكل دوري.
            </p>
        </section>

        <!-- Section: Law -->
        <section id="law" class="mb-12 scroll-mt-32">
            <h2 class="text-2xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-4 flex items-center gap-3">
                <span class="w-1 h-7 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                القانون المُطبَّق
            </h2>
            <p class="text-[14px] text-ink-body leading-[1.9]">
                تخضع هذه الشروط لأنظمة المملكة العربية السعودية، وأي نزاع ينشأ عن استخدام المنصة يُحال إلى الجهات
                القضائية المختصّة في المملكة العربية السعودية.
            </p>
        </section>

        <!-- Section: Contact -->
        <section id="contact" class="scroll-mt-32">
            <h2 class="text-2xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-4 flex items-center gap-3">
                <span class="w-1 h-7 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                تواصل معنا
            </h2>
            <p class="text-[14px] text-ink-body leading-[1.9] mb-5">
                لأي استفسار قانوني أو للاطلاع على المزيد من التفاصيل حول هذه الشروط:
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <a href="mailto:info@rowaad.org" class="group flex items-center gap-3 p-4 rounded-2xl bg-canvas border border-soft hover:border-[#3DAFB9]/40 transition-all">
                    <span class="w-10 h-10 rounded-xl bg-[#3DAFB9]/12 flex items-center justify-center">
                        <svg class="w-4.5 h-4.5 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </span>
                    <div>
                        <div class="text-[10px] text-ink-muted tracking-wider uppercase">البريد الإلكتروني</div>
                        <div class="text-[13px] font-black text-ink group-hover:text-[#3DAFB9] transition-colors" dir="ltr">info@rowaad.org</div>
                    </div>
                </a>
                <Link href="/contact" class="group flex items-center gap-3 p-4 rounded-2xl bg-canvas border border-soft hover:border-[#3DAFB9]/40 transition-all">
                    <span class="w-10 h-10 rounded-xl bg-[#3DAFB9]/12 flex items-center justify-center">
                        <svg class="w-4.5 h-4.5 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </span>
                    <div>
                        <div class="text-[10px] text-ink-muted tracking-wider uppercase">صفحة التواصل</div>
                        <div class="text-[13px] font-black text-ink group-hover:text-[#3DAFB9] transition-colors">أرسل استفسارك</div>
                    </div>
                </Link>
            </div>
        </section>
    </LegalShell>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import LegalShell from '@/Components/Legal/LegalShell.vue';

const toc = [
    { id: 'services',    title: 'نطاق الخدمات' },
    { id: 'account',     title: 'الحساب والاستخدام' },
    { id: 'rights',      title: 'حقوق المستخدم' },
    { id: 'bookings',    title: 'الحجوزات والمدفوعات' },
    { id: 'consultants', title: 'شروط المستشارين' },
    { id: 'ip',          title: 'الملكية الفكرية' },
    { id: 'duties',      title: 'التزامات رواد' },
    { id: 'changes',     title: 'تغييرات على الشروط' },
    { id: 'law',         title: 'القانون المُطبَّق' },
    { id: 'contact',     title: 'تواصل معنا' },
];

const services = [
    { title: 'الاستشارات الاقتصادية', desc: 'تحليل الأسواق واتخاذ القرارات المالية.' },
    { title: 'دراسات الجدوى',         desc: 'دراسات متكاملة (فنية، مالية، تسويقية).' },
    { title: 'التخطيط الاستراتيجي',    desc: 'خرائط طريق واضحة لتحقيق أهدافك.' },
    { title: 'الحوكمة والامتثال',      desc: 'أنظمة الحوكمة والالتزام التنظيمي.' },
    { title: 'التسويق وتطوير الأعمال', desc: 'حلول مبتكرة للنمو والتوسّع.' },
    { title: 'الاستشارات التقنية',     desc: 'التحول الرقمي وحلول الذكاء الاصطناعي.' },
];

const accountRules = [
    'تُقدَّم بيانات صحيحة ودقيقة عند التسجيل، وتلتزم بتحديثها عند تغيّرها.',
    'أنت مسؤول عن الحفاظ على سرّية بيانات دخولك (البريد وكلمة المرور).',
    'أي نشاط يتم من خلال حسابك يُعدّ صادراً عنك، ما لم تُخطرنا فوراً بأي اختراق.',
    'الحساب مخصّص للاستخدام الشخصي، ولا يجوز مشاركته مع أي طرف ثالث.',
    'يحقّ لنا تعليق أو إنهاء أي حساب يُخالف هذه الشروط دون إشعار مسبق.',
];

const allowed = [
    'استخدام المنصة للأغراض الاستشارية المشروعة',
    'حجز الاستشارات وتصفّح المستشارين المعتمدين',
    'شراء وتنزيل دراسات الجدوى المتاحة',
    'التواصل معنا لأي استفسار أو دعم',
    'إلغاء الحجز وفقاً لسياسة الإلغاء',
];

const forbidden = [
    'نسخ أو إعادة نشر محتوى المنصة تجارياً',
    'إنشاء حسابات وهميّة أو انتحال شخصية الآخرين',
    'محاولة اختراق أو تعطيل خدمات المنصة',
    'استخدام المنصة لأي غرض غير قانوني',
    'التواصل خارج المنصة لتجاوز عمولاتها',
];

const bookingRules = [
    'جميع الأسعار مُعلَنة بالريال السعودي وشاملة ما يترتّب عليها من رسوم.',
    'الدفع يتمّ عبر بوابات دفع آمنة معتمدة، ولا تُخزَّن بيانات بطاقتك لدينا.',
    'يتم تأكيد الحجز فور اكتمال عملية الدفع بنجاح.',
    'في حالة اعتذار المستشار عن موعد مؤكّد، يُستردّ المبلغ كاملاً.',
    'يجب إلغاء الحجز قبل الموعد بـ 24 ساعة على الأقل للحصول على استرداد كامل.',
    'الاستشارات تتم عبر منصات موثوقة (Zoom أو ما يُحدَّد من قِبَل المستشار).',
];

const consultantRules = [
    'استيفاء نموذج التقديم الكامل بجميع البيانات المطلوبة والوثائق الداعمة.',
    'الحصول على اعتماد رسمي من إدارة المنصة قبل استقبال أي حجوزات.',
    'الالتزام بالمواعيد المُتَّفَق عليها مع العملاء وتقديم استشارات باحترافية.',
    'الحفاظ على سرّية معلومات العملاء وعدم استخدامها خارج نطاق الاستشارة.',
    'الالتزام بنسبة المنصة والزكاة المُتّفَق عليها من قيمة كل جلسة.',
    'تحديث الملف الشخصي والخدمات المُقدَّمة بشكل دوري.',
];

const duties = [
    { title: 'حماية البيانات',   desc: 'حفظ بيانات المستخدمين وعدم الإفصاح عنها لأي جهة.' },
    { title: 'حلّ المشكلات',      desc: 'الاستجابة الفورية لأي مشكلة تواجهك أثناء استخدام الخدمة.' },
    { title: 'قنوات تواصل',       desc: 'توفير قنوات تواصل مستمرّة وفعّالة للدعم والاستفسارات.' },
    { title: 'جودة الخدمة',       desc: 'ضمان اعتماد مستشارين مؤهّلين ومحتوى موثوق.' },
    { title: 'التسليم في الوقت',  desc: 'تسليم الدراسات المُشتراة في أسرع وقت ممكن.' },
    { title: 'الشفافية',          desc: 'وضوح الأسعار والعمولات وسياسات الاسترداد.' },
];
</script>

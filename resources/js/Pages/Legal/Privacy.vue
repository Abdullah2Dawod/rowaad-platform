<template>
    <Head title="سياسة الخصوصية" />
    <LegalShell
        eyebrow="السياسات · الوثائق القانونية"
        last-updated="2026-07-04"
        :toc="toc"
        other-label="شروط الاستخدام"
        other-hint="القواعد المُنظِّمة لاستخدامك للمنصة"
        other-href="/terms"
        other-cta="اقرأ شروط الاستخدام"
    >
        <template #title>سياسة الخصوصية وحماية البيانات</template>
        <template #subtitle>
            نحن في <span class="font-black text-ink">رواد بلا حدود</span> نأخذ خصوصيّتك بمنتهى الجدّية.
            توضّح هذه الوثيقة كيفية جمعنا لبياناتك واستخدامها وحمايتها، والتزامنا الكامل بأعلى معايير السرّية والأمان.
        </template>

        <!-- Intro -->
        <div class="mb-10 p-5 rounded-2xl bg-gradient-to-br from-[#3DAFB9]/8 to-[#2D4B7E]/5 border border-[#3DAFB9]/20">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#3DAFB9]/15 border border-[#3DAFB9]/25 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <p class="text-[13.5px] text-ink-body leading-[1.9]">
                    كافة البيانات التي نحصل عليها من عملائنا تتم في <span class="font-bold text-ink">سرّية تامة</span>،
                    ولا يمكن لأي شخص الاطلاع عليها. نأخذ الأوضاع القانونية على محمل الجدّ الذي يضمن حماية بياناتك
                    وعدم إفشائها لأي جهة غير مُختصّة.
                </p>
            </div>
        </div>

        <!-- Section 1 -->
        <section id="collect" class="mb-12 scroll-mt-32">
            <h2 class="text-2xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-4 flex items-center gap-3">
                <span class="w-1 h-7 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                البيانات التي نجمعها
            </h2>
            <p class="text-[14px] text-ink-body leading-[1.9] mb-4">
                عند استخدامك لخدمات منصة رواد، قد نقوم بجمع بعض المعلومات لتمكيننا من تقديم خدمة أفضل وأكثر تخصيصاً.
                نجمع هذه البيانات فقط بموافقتك المباشرة أو من خلال تفاعلك الطبيعي مع المنصة.
            </p>
            <ul class="space-y-3">
                <li v-for="(item, i) in collectItems" :key="i" class="flex items-start gap-3 p-3 rounded-xl border border-soft hover:border-[#3DAFB9]/30 transition-colors">
                    <span class="w-6 h-6 rounded-lg bg-[#3DAFB9]/12 text-[#3DAFB9] flex items-center justify-center text-[11px] font-black shrink-0 mt-0.5">{{ i + 1 }}</span>
                    <div class="flex-1">
                        <div class="text-[13.5px] font-bold text-ink mb-0.5">{{ item.title }}</div>
                        <div class="text-[12.5px] text-ink-body leading-relaxed">{{ item.desc }}</div>
                    </div>
                </li>
            </ul>
        </section>

        <!-- Section 2 -->
        <section id="use" class="mb-12 scroll-mt-32">
            <h2 class="text-2xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-4 flex items-center gap-3">
                <span class="w-1 h-7 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                كيف نستخدم بياناتك
            </h2>
            <p class="text-[14px] text-ink-body leading-[1.9] mb-4">
                نستخدم بياناتك لأغراض محدّدة ومشروعة تصبّ جميعها في مصلحتك كعميل، وتُمكّننا من تقديم استشارات
                عالية الجودة تتناسب مع احتياجاتك.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div v-for="(u, i) in useItems" :key="i" class="p-4 rounded-xl bg-canvas border border-soft">
                    <div class="flex items-center gap-2.5 mb-1.5">
                        <span class="w-7 h-7 rounded-lg bg-gradient-to-br from-[#3DAFB9]/15 to-[#2D4B7E]/10 border border-[#3DAFB9]/25 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span class="text-[13px] font-black text-ink">{{ u.title }}</span>
                    </div>
                    <p class="text-[12px] text-ink-body leading-relaxed">{{ u.desc }}</p>
                </div>
            </div>
        </section>

        <!-- Section 3 -->
        <section id="protect" class="mb-12 scroll-mt-32">
            <h2 class="text-2xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-4 flex items-center gap-3">
                <span class="w-1 h-7 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                حماية معلوماتك
            </h2>
            <p class="text-[14px] text-ink-body leading-[1.9] mb-3">
                نتبع جميع الإجراءات الصحيحة ونطبّق أعلى معايير الأمان لحماية بياناتك وأفكارك الاستثمارية.
                تشمل هذه الإجراءات:
            </p>
            <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <li v-for="(p, i) in protectItems" :key="i" class="flex items-start gap-2.5 p-3 rounded-lg bg-canvas">
                    <svg class="w-4 h-4 text-[#3DAFB9] shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span class="text-[13px] text-ink-body">{{ p }}</span>
                </li>
            </ul>
        </section>

        <!-- Section 4 -->
        <section id="share" class="mb-12 scroll-mt-32">
            <h2 class="text-2xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-4 flex items-center gap-3">
                <span class="w-1 h-7 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                مشاركة معلوماتك
            </h2>
            <div class="p-5 rounded-2xl bg-red-500/8 border border-red-500/20 mb-4">
                <p class="text-[14px] font-bold text-ink mb-2">التزامنا الحاسم:</p>
                <p class="text-[13px] text-ink-body leading-[1.9]">
                    نحن في رواد <span class="font-bold text-ink">ملتزمون بعدم مشاركة أو تداول أي معلومات شخصية</span>
                    مع أي جهة خارجية دون إذنك الصريح، إلا في الحالات التي يُلزمنا بها القانون.
                </p>
            </div>
            <p class="text-[13.5px] text-ink-body leading-[1.9]">
                نطلب منك عدم مشاركة بياناتك مع أي شخص أو موقع آخر، حيث لا نتحمّل أي مسؤولية عن حدوث اختراق
                نتيجة مشاركتك لبياناتك مع أطراف ثالثة.
            </p>
        </section>

        <!-- Section 5 -->
        <section id="cookies" class="mb-12 scroll-mt-32">
            <h2 class="text-2xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-4 flex items-center gap-3">
                <span class="w-1 h-7 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                ملفات تعريف الارتباط (Cookies)
            </h2>
            <p class="text-[14px] text-ink-body leading-[1.9]">
                نستخدم ملفات تعريف الارتباط (Cookies) لجمع بعض المعلومات الشخصية بهدف تحسين تجربتك على المنصة.
                يمكنك تعطيل هذه الملفات من إعدادات متصفّحك، لكن ذلك قد يؤثّر على بعض ميزات الموقع.
            </p>
        </section>

        <!-- Section 6 -->
        <section id="external" class="mb-12 scroll-mt-32">
            <h2 class="text-2xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-4 flex items-center gap-3">
                <span class="w-1 h-7 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                روابط لمواقع أخرى
            </h2>
            <p class="text-[14px] text-ink-body leading-[1.9]">
                قد يحتوي موقعنا على روابط لمواقع خارجية. نحن لا نملك السيطرة على هذه المواقع ولا نتحمّل أي مسؤولية
                عن محتواها أو ممارساتها المتعلّقة بالخصوصية. نشجّعك على مراجعة سياسات الخصوصية الخاصة بكل موقع تزوره.
            </p>
        </section>

        <!-- Section 7 -->
        <section id="changes" class="mb-12 scroll-mt-32">
            <h2 class="text-2xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-4 flex items-center gap-3">
                <span class="w-1 h-7 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                تغييرات على السياسة
            </h2>
            <p class="text-[14px] text-ink-body leading-[1.9]">
                نحتفظ بكامل الحق في تغيير أو تعديل سياسة الخصوصية الخاصة بموقعنا في أي وقت. سيتم إبلاغك بأي تغييرات
                جوهرية، ويُعدّ استمرارك في استخدام المنصة موافقة على السياسات المحدَّثة.
            </p>
        </section>

        <!-- Section 8 -->
        <section id="contact" class="scroll-mt-32">
            <h2 class="text-2xl font-black text-[#2D4B7E] dark:text-[#C2EBEF] mb-4 flex items-center gap-3">
                <span class="w-1 h-7 rounded-full bg-gradient-to-b from-[#3DAFB9] to-[#2D4B7E]"></span>
                تواصل معنا
            </h2>
            <p class="text-[14px] text-ink-body leading-[1.9] mb-5">
                إذا كانت لديك أي أسئلة أو استفسارات بخصوص سياسة الخصوصية، لا تتردّد في التواصل معنا:
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
    { id: 'collect',  title: 'البيانات التي نجمعها' },
    { id: 'use',      title: 'كيف نستخدم بياناتك' },
    { id: 'protect',  title: 'حماية معلوماتك' },
    { id: 'share',    title: 'مشاركة معلوماتك' },
    { id: 'cookies',  title: 'ملفات تعريف الارتباط' },
    { id: 'external', title: 'روابط خارجية' },
    { id: 'changes',  title: 'التغييرات على السياسة' },
    { id: 'contact',  title: 'تواصل معنا' },
];

const collectItems = [
    { title: 'المعلومات الشخصية', desc: 'الاسم، البريد الإلكتروني، رقم الجوال، والمعلومات المهنية التي تُدخلها طوعاً.' },
    { title: 'بيانات الاستخدام',   desc: 'معلومات حول زياراتك للموقع، مدة الجلسات، وأبرز المحتويات التي تم عرضها.' },
    { title: 'المعلومات المهنية',   desc: 'للمستشارين: شهاداتك، خبراتك، تخصصاتك، ووثائق الاعتماد.' },
    { title: 'بيانات المعاملات',    desc: 'تفاصيل الحجوزات والمدفوعات (نستخدم بوابات دفع آمنة ولا نُخزّن بيانات البطاقات).' },
];

const useItems = [
    { title: 'تقديم الخدمة',        desc: 'ربطك بالمستشار المناسب وتيسير حجوزاتك.' },
    { title: 'تحسين الجودة',        desc: 'تطوير خدماتنا بناءً على تفاعلك مع المنصة.' },
    { title: 'التحقّق من الهوية',   desc: 'ضمان صحّة بياناتك وحماية حسابك.' },
    { title: 'إشعارات مهمة',        desc: 'إبلاغك بمواعيد الجلسات، الاعتماد، وأي تحديثات جوهرية.' },
    { title: 'دعم فنّي',            desc: 'مساعدتك عند مواجهة أي مشكلة تقنية أو استفسار.' },
    { title: 'التزامات قانونية',     desc: 'الامتثال للقوانين والأنظمة السعودية المعمول بها.' },
];

const protectItems = [
    'تشفير SSL/TLS لجميع الاتصالات مع المنصة',
    'تشفير كلمات المرور باستخدام خوارزميات قوية',
    'حماية بوابات الدفع بمعايير PCI DSS',
    'نُسخ احتياطية دورية لضمان استمرارية الخدمة',
    'وصول مقيّد للبيانات على أساس مبدأ "الحاجة إلى المعرفة"',
    'مراجعات أمنية دورية للبنية التحتية',
];
</script>

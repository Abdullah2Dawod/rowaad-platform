<template>
    <MainLayout>
        <section class="relative pt-32 lg:pt-40 pb-20 bg-paper overflow-hidden">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute inset-0 grid-pattern opacity-40"></div>
                <div class="absolute top-0 right-[10%] w-[500px] h-[500px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(61,175,185,0.10), transparent 70%);"></div>
                <div class="absolute bottom-0 left-[8%] w-[420px] h-[420px] rounded-full aurora-drift"
                     style="background: radial-gradient(circle, rgba(45,75,126,0.10), transparent 70%); animation-delay: 3s;"></div>
            </div>

            <div class="relative max-w-6xl mx-auto px-6 lg:px-10">
                <!-- Just-verified flash -->
                <transition
                    enter-active-class="transition duration-500 ease-out"
                    enter-from-class="opacity-0 -translate-y-2"
                    enter-to-class="opacity-100 translate-y-0"
                >
                    <div v-if="verifiedFlash" class="mb-6 p-4 rounded-2xl bg-green-500/10 border border-green-500/25 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-green-500 flex items-center justify-center shrink-0">
                            <svg class="w-4.5 h-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <div class="text-[14px] font-black text-green-700 dark:text-green-400">تم توثيق بريدك الإلكتروني!</div>
                            <div class="text-[12px] text-ink-body">أصبح بإمكانك الآن حجز الاستشارات.</div>
                        </div>
                    </div>
                </transition>

                <!-- Header -->
                <div class="flex flex-wrap items-center gap-5 mb-8">
                    <!-- Avatar with edit affordance -->
                    <div class="relative group shrink-0">
                        <div class="w-20 h-20 rounded-2xl overflow-hidden bg-gradient-to-br from-[#3DAFB9]/15 to-[#2D4B7E]/10 border border-[#3DAFB9]/25 flex items-center justify-center">
                            <img v-if="profile.avatar_url" :src="profile.avatar_url" alt=""
                                 class="w-full h-full object-cover" />
                            <span v-else class="text-[#3DAFB9] text-3xl font-black">{{ initials }}</span>
                        </div>
                        <button v-if="!profile.is_verified" disabled class="absolute -bottom-1.5 -right-1.5 rtl:-right-auto rtl:-left-1.5 w-6 h-6 rounded-full bg-orange-500 border-2 border-paper flex items-center justify-center shadow" title="غير موثّق">
                            <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" d="M12 9v2m0 4h.01M4.93 19h14.14a2 2 0 001.75-2.97l-7.07-12a2 2 0 00-3.5 0l-7.07 12A2 2 0 004.93 19z"/></svg>
                        </button>
                        <div v-else class="absolute -bottom-1.5 -right-1.5 rtl:-right-auto rtl:-left-1.5 w-6 h-6 rounded-full bg-gradient-to-br from-[#3DAFB9] to-[#2D4B7E] border-2 border-paper flex items-center justify-center shadow" title="موثّق">
                            <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <div class="text-[11px] text-[#3DAFB9] tracking-[0.3em] uppercase font-bold">حسابي</div>
                            <span v-if="profile.is_verified" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-500/10 border border-green-500/25 text-green-600 text-[10px] font-black">
                                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4M12 2a10 10 0 100 20 10 10 0 000-20z"/></svg>
                                موثّق
                            </span>
                            <span v-else class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-orange-500/10 border border-orange-500/25 text-orange-600 text-[10px] font-black">
                                غير موثّق
                            </span>
                        </div>
                        <h1 class="text-2xl lg:text-3xl font-black text-[#2D4B7E] dark:text-[#C2EBEF]">أهلاً {{ profile.name }}</h1>
                        <p class="text-[13px] text-ink-body">عضو منذ {{ profile.created_at }}</p>
                    </div>

                    <form @submit.prevent="logout">
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-elevated border border-soft text-ink-body text-[12px] font-bold hover:border-red-500/40 hover:text-red-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            تسجيل خروج
                        </button>
                    </form>
                </div>

                <!-- Read-only banner for admin/consultant -->
                <div v-if="!canEdit" class="mb-8 rounded-[1.5rem] overflow-hidden border border-[#3DAFB9]/25 bg-gradient-to-br from-[#3DAFB9]/8 to-[#2D4B7E]/5">
                    <div class="p-5 flex items-center gap-4">
                        <div class="w-11 h-11 rounded-2xl bg-[#3DAFB9]/15 border border-[#3DAFB9]/25 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-[13.5px] font-black text-ink">هذه الصفحة عرض فقط</div>
                            <p class="text-[12px] text-ink-body leading-relaxed">
                                بصفتك <span class="font-bold">{{ profile.role === 'admin' ? 'مديراً' : 'مستشاراً' }}</span>،
                                يتم إدارة معلوماتك الشخصية من لوحة التحكم. هنا يظهر لك فقط الحجوزات التي أجريتها كعميل تجريبي.
                            </p>
                        </div>
                        <a href="/admin" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[12px] font-bold shadow-md hover:scale-105 transition-transform">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3"/></svg>
                            لوحة التحكم
                        </a>
                    </div>
                </div>

                <!-- Unverified banner (regular users only) -->
                <div v-if="canEdit && !profile.is_verified" class="mb-8 rounded-[1.5rem] overflow-hidden border border-orange-500/25 bg-gradient-to-br from-orange-500/8 to-orange-500/4">
                    <div class="p-6 flex flex-col md:flex-row items-start md:items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center shrink-0 shadow-lg shadow-orange-500/25">
                            <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-[15px] font-black text-ink mb-1">وثّق بريدك الإلكتروني لتتمكّن من الحجز</div>
                            <p class="text-[13px] text-ink-body leading-relaxed">
                                أرسلنا رابط التوثيق إلى <span class="font-bold text-ink" dir="ltr">{{ profile.email }}</span>. افتح البريد واضغط الرابط.
                                <span class="block mt-1 text-ink-muted text-[12px]">لن تتمكّن من حجز أي استشارة قبل توثيق البريد.</span>
                            </p>
                        </div>
                        <form @submit.prevent="resendVerification">
                            <button type="submit" :disabled="resending"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 text-white text-[13px] font-black shadow-md hover:scale-105 disabled:opacity-60 transition-transform whitespace-nowrap">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                {{ resending ? 'جاري الإرسال...' : 'إعادة إرسال البريد' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div v-for="s in statsView" :key="s.label" class="p-5 rounded-2xl bg-elevated border border-soft flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center" :class="s.iconBg">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" v-html="s.icon"></svg>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-ink">{{ s.value }}</div>
                            <div class="text-[11px] text-ink-muted tracking-wider mt-0.5">{{ s.label }}</div>
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="mb-6 flex items-center gap-1 rounded-full bg-elevated border border-soft p-1 max-w-full overflow-x-auto">
                    <button v-for="t in tabs" :key="t.id" @click="tab = t.id"
                            :class="['shrink-0 px-4 py-2 rounded-full text-[12.5px] font-bold transition-all whitespace-nowrap',
                                     tab === t.id ? 'bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white shadow-sm'
                                                 : 'text-ink-body hover:text-ink']">
                        {{ t.label }}
                    </button>
                </div>

                <!-- Status flash -->
                <div v-if="status" class="mb-5 p-3 rounded-2xl bg-green-500/10 border border-green-500/25 text-[13px] text-green-700 dark:text-green-400 font-bold">
                    {{ status }}
                </div>

                <!-- ========== TAB: INFO (read-only for admin/consultant) ========== -->
                <div v-if="tab === 'info' && !canEdit" class="max-w-2xl">
                    <div class="rounded-[1.5rem] bg-elevated border border-soft p-7 shadow-card">
                        <h2 class="text-lg font-black text-ink mb-1">بيانات حسابك</h2>
                        <p class="text-[12px] text-ink-body mb-6">لا يمكن تعديلها من هنا — راجع لوحة التحكم.</p>
                        <dl class="space-y-3 text-[13.5px] divide-y divide-[var(--border-soft)]">
                            <div class="flex items-center justify-between py-2">
                                <dt class="text-ink-muted">الاسم</dt>
                                <dd class="font-bold text-ink">{{ profile.name }}</dd>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <dt class="text-ink-muted">البريد الإلكتروني</dt>
                                <dd class="font-bold text-ink" dir="ltr">{{ profile.email }}</dd>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <dt class="text-ink-muted">الجوال</dt>
                                <dd class="font-bold text-ink" dir="ltr">{{ profile.phone || '—' }}</dd>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <dt class="text-ink-muted">الدور</dt>
                                <dd>
                                    <span :class="['inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-black',
                                                   profile.role === 'admin' ? 'bg-red-500/10 text-red-600' : 'bg-blue-500/10 text-blue-600']">
                                        {{ profile.role === 'admin' ? 'مدير' : 'مستشار' }}
                                    </span>
                                </dd>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <dt class="text-ink-muted">تاريخ التسجيل</dt>
                                <dd class="font-bold text-ink">{{ profile.created_at }}</dd>
                            </div>
                        </dl>
                        <a href="/admin" class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[13px] font-bold shadow-md hover:scale-105 transition-transform">
                            الذهاب إلى لوحة التحكم
                            <svg class="w-3.5 h-3.5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>

                <!-- ========== TAB: PROFILE ========== -->
                <div v-if="tab === 'profile' && canEdit" class="grid grid-cols-12 gap-5">
                    <!-- Avatar card -->
                    <div class="col-span-12 lg:col-span-4">
                        <div class="rounded-[1.5rem] bg-elevated border border-soft p-6 shadow-card text-center">
                            <div class="relative w-32 h-32 mx-auto mb-5">
                                <div class="w-full h-full rounded-3xl overflow-hidden bg-gradient-to-br from-[#3DAFB9]/15 to-[#2D4B7E]/10 border-2 border-[#3DAFB9]/25 flex items-center justify-center">
                                    <img v-if="avatarPreview" :src="avatarPreview" alt="" class="w-full h-full object-cover" />
                                    <span v-else class="text-[#3DAFB9] text-5xl font-black">{{ initials }}</span>
                                </div>
                            </div>
                            <h3 class="text-[14px] font-black text-ink mb-1">الصورة الشخصية</h3>
                            <p class="text-[12px] text-ink-body mb-5">JPG / PNG / WEBP · حتى 4 ميجا</p>

                            <div class="flex items-center justify-center gap-2">
                                <label class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[12px] font-bold cursor-pointer shadow-md hover:scale-105 transition-transform">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5-5m0 0l5 5m-5-5v12"/></svg>
                                    اختر صورة
                                    <input type="file" accept="image/*" class="sr-only" @change="onAvatar" />
                                </label>
                                <button v-if="profile.avatar_url" @click="deleteAvatar" type="button"
                                        class="inline-flex items-center px-3 py-2 rounded-full bg-elevated border border-soft text-ink-body text-[12px] font-bold hover:border-red-500/40 hover:text-red-500 transition-colors">
                                    حذف
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Personal info form -->
                    <div class="col-span-12 lg:col-span-8">
                        <div class="rounded-[1.5rem] bg-elevated border border-soft p-7 shadow-card">
                            <h2 class="text-lg font-black text-ink mb-1">البيانات الشخصية</h2>
                            <p class="text-[12px] text-ink-body mb-6">حدّث اسمك، بريدك، وجوالك.</p>

                            <form @submit.prevent="profileForm.patch('/profile', { preserveScroll: true })" class="space-y-4">
                                <FormField label="الاسم الكامل" required :error="profileForm.errors.name">
                                    <input v-model="profileForm.name" type="text" class="fld" />
                                </FormField>

                                <FormField label="البريد الإلكتروني" required
                                           :hint="profileForm.email !== profile.email ? 'ستحتاج لإعادة التوثيق' : null"
                                           :error="profileForm.errors.email">
                                    <input v-model="profileForm.email" type="email" dir="ltr" class="fld" />
                                </FormField>

                                <FormField label="رقم الجوال" :error="profileForm.errors.phone">
                                    <input v-model="profileForm.phone" type="tel" dir="ltr" class="fld" placeholder="+9665XXXXXXXX" />
                                </FormField>

                                <div class="flex justify-end pt-2">
                                    <button type="submit" :disabled="profileForm.processing"
                                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[13px] font-black shadow-md hover:scale-[1.03] disabled:opacity-60 transition-transform">
                                        <span v-if="profileForm.processing" class="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                        حفظ التغييرات
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- ========== TAB: SECURITY ========== -->
                <div v-if="tab === 'security' && canEdit" class="grid grid-cols-12 gap-5">
                    <div class="col-span-12 lg:col-span-7">
                        <div class="rounded-[1.5rem] bg-elevated border border-soft p-7 shadow-card">
                            <h2 class="text-lg font-black text-ink mb-1">تغيير كلمة المرور</h2>
                            <p class="text-[12px] text-ink-body mb-6">يُوصى باستخدام كلمة مرور قوية لا تستخدمها في مواقع أخرى.</p>

                            <form @submit.prevent="submitPassword" class="space-y-4">
                                <FormField label="كلمة المرور الحالية" required :error="passwordForm.errors.current_password">
                                    <input v-model="passwordForm.current_password" type="password" dir="ltr" class="fld" autocomplete="current-password" />
                                </FormField>
                                <FormField label="كلمة المرور الجديدة" required :error="passwordForm.errors.password">
                                    <input v-model="passwordForm.password" type="password" dir="ltr" class="fld" autocomplete="new-password" />
                                </FormField>
                                <FormField label="تأكيد كلمة المرور" required>
                                    <input v-model="passwordForm.password_confirmation" type="password" dir="ltr" class="fld" autocomplete="new-password" />
                                </FormField>
                                <div class="flex justify-end pt-2">
                                    <button type="submit" :disabled="passwordForm.processing"
                                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[13px] font-black shadow-md hover:scale-[1.03] disabled:opacity-60 transition-transform">
                                        تحديث كلمة المرور
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-span-12 lg:col-span-5">
                        <div class="rounded-[1.5rem] bg-elevated border border-soft p-6 shadow-card">
                            <h3 class="text-[13px] font-black text-ink-muted tracking-widest uppercase mb-4">حالة الحساب</h3>
                            <dl class="space-y-3 text-[13px]">
                                <div class="flex items-center justify-between">
                                    <dt class="text-ink-muted">البريد</dt>
                                    <dd class="font-bold text-ink" dir="ltr">{{ profile.email }}</dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-ink-muted">حالة التوثيق</dt>
                                    <dd>
                                        <span v-if="profile.is_verified" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-500/10 text-green-600 text-[11px] font-black">
                                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4M12 2a10 10 0 100 20 10 10 0 000-20z"/></svg>
                                            موثّق
                                        </span>
                                        <span v-else class="inline-flex px-2 py-0.5 rounded-full bg-orange-500/10 text-orange-600 text-[11px] font-black">
                                            غير موثّق
                                        </span>
                                    </dd>
                                </div>
                                <div v-if="profile.verified_at" class="flex items-center justify-between">
                                    <dt class="text-ink-muted">تاريخ التوثيق</dt>
                                    <dd class="font-bold text-ink">{{ new Date(profile.verified_at).toLocaleDateString('ar-SA') }}</dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-ink-muted">تاريخ التسجيل</dt>
                                    <dd class="font-bold text-ink">{{ profile.created_at }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- ========== TAB: BOOKINGS ========== -->
                <div v-if="tab === 'bookings'">
                    <div class="rounded-[1.5rem] bg-elevated border border-soft p-7 shadow-card">
                        <div class="flex items-baseline justify-between mb-5">
                            <h2 class="text-lg font-black text-ink">حجوزاتي</h2>
                            <Link href="/bookings" class="text-[12px] text-[#3DAFB9] font-bold hover:underline">عرض الكل</Link>
                        </div>

                        <div v-if="!bookings.length" class="py-12 text-center">
                            <div class="w-14 h-14 rounded-full bg-[#3DAFB9]/10 mx-auto flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-[#3DAFB9]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><rect x="3" y="4" width="18" height="18" rx="2"/><path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/></svg>
                            </div>
                            <p class="text-[13px] text-ink-body mb-4">لم تحجز أي استشارة بعد.</p>
                            <Link href="/consultants" class="inline-flex px-5 py-2 rounded-full bg-gradient-to-l rtl:bg-gradient-to-r from-[#2D4B7E] to-[#3DAFB9] text-white text-[12.5px] font-bold shadow-md hover:scale-105 transition-transform">
                                تصفّح المستشارين
                            </Link>
                        </div>

                        <div v-else class="space-y-2.5">
                            <Link v-for="b in bookings" :key="b.id" :href="`/bookings/${b.id}`"
                                  class="group flex items-center gap-3 p-3 rounded-2xl border border-soft hover:border-[#3DAFB9]/40 hover:bg-canvas transition-all">
                                <img v-if="b.consultant.avatar" :src="b.consultant.avatar" :alt="b.consultant.name"
                                     class="w-11 h-11 rounded-full object-cover shrink-0" />
                                <div v-else class="w-11 h-11 rounded-full bg-[#3DAFB9]/15 text-[#3DAFB9] flex items-center justify-center font-black shrink-0">
                                    {{ b.consultant.name?.charAt(0) || '?' }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-[13px] font-black text-ink group-hover:text-[#3DAFB9] transition-colors truncate">{{ b.consultant.name }}</div>
                                    <div class="text-[11px] text-ink-muted mt-0.5">
                                        {{ b.preferred_date }} · {{ b.preferred_time }} · {{ b.duration_min }} دقيقة
                                    </div>
                                </div>
                                <div class="text-left rtl:text-left shrink-0">
                                    <div class="text-[13px] font-black text-ink">{{ formatPrice(b.amount) }} <span class="text-[9px] text-ink-muted">ر.س</span></div>
                                    <span :class="['inline-block mt-1 text-[9px] font-bold px-2 py-0.5 rounded-full', statusColor(b.status)]">{{ statusLabel(b.status) }}</span>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </MainLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import FormField from '@/Components/Apply/FormField.vue';

const props = defineProps({
    profile:       { type: Object, required: true },
    bookings:      { type: Array, default: () => [] },
    stats:         { type: Object, default: () => ({ total_bookings: 0, completed: 0, upcoming: 0 }) },
    status:        String,
    verifiedFlash: Boolean,
    canEdit:       { type: Boolean, default: true }, // false for admin/consultant
});

const initials = computed(() => (props.profile.name || '?').charAt(0));
const avatarPreview = ref(props.profile.avatar_url);

const tab = ref(props.canEdit ? 'profile' : 'bookings');
const tabs = props.canEdit
    ? [
        { id: 'profile',  label: 'البيانات الشخصية' },
        { id: 'security', label: 'الأمان وكلمة المرور' },
        { id: 'bookings', label: 'حجوزاتي' },
      ]
    : [
        { id: 'bookings', label: 'حجوزاتي التجريبية' },
        { id: 'info',     label: 'بيانات حسابي' },
      ];

const profileForm = useForm({
    name:  props.profile.name || '',
    email: props.profile.email || '',
    phone: props.profile.phone || '',
});

const passwordForm = useForm({
    current_password:      '',
    password:              '',
    password_confirmation: '',
});
const submitPassword = () =>
    passwordForm.patch('/profile/password', {
        preserveScroll: true,
        onSuccess: () => passwordForm.reset(),
    });

// Avatar upload
const onAvatar = (e) => {
    const file = e.target.files?.[0];
    if (!file) return;
    avatarPreview.value = URL.createObjectURL(file);
    router.post('/profile/avatar', { avatar: file }, {
        forceFormData: true,
        preserveScroll: true,
    });
};
const deleteAvatar = () => {
    if (! confirm('حذف الصورة الشخصية؟')) return;
    router.delete('/profile/avatar', { preserveScroll: true });
    avatarPreview.value = null;
};

// Resend verification
const resending = ref(false);
const resendVerification = () => {
    resending.value = true;
    router.post('/email/verification-notification', {}, {
        preserveScroll: true,
        onFinish: () => (resending.value = false),
    });
};

const statsView = computed(() => [
    { label: 'إجمالي الحجوزات', value: props.stats.total_bookings, iconBg: 'bg-gradient-to-br from-[#2D4B7E] to-[#3DAFB9]',
      icon: '<rect x="3" y="4" width="18" height="18" rx="2"/><path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/>' },
    { label: 'قادمة', value: props.stats.upcoming, iconBg: 'bg-gradient-to-br from-orange-400 to-orange-600',
      icon: '<circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 6v6l4 2"/>' },
    { label: 'مكتملة', value: props.stats.completed, iconBg: 'bg-gradient-to-br from-green-500 to-green-600',
      icon: '<path stroke-linecap="round" d="M5 13l4 4L19 7"/>' },
]);

const formatPrice = (v) => new Intl.NumberFormat('ar-SA').format(Math.round(v));
const statusLabel = (s) => ({ pending_payment: 'بانتظار الدفع', paid: 'مدفوع', confirmed: 'مؤكّد', cancelled: 'ملغى', completed: 'مكتمل' }[s] || s);
const statusColor = (s) => ({
    pending_payment: 'bg-orange-500/12 text-orange-500',
    paid:            'bg-blue-500/12 text-blue-500',
    confirmed:       'bg-green-500/12 text-green-600',
    cancelled:       'bg-red-500/12 text-red-500',
    completed:       'bg-gray-500/12 text-gray-500',
}[s] || 'bg-gray-500/12 text-gray-500');

const logout = () => router.post('/logout');
</script>

<style scoped>
.fld {
    display: block; width: 100%; height: 44px; padding: 0 14px;
    background: var(--bg-canvas); border: 1px solid var(--border-soft);
    border-radius: 12px; color: var(--ink-primary);
    font-size: 13.5px; font-family: inherit;
    transition: border-color 200ms ease, box-shadow 200ms ease;
}
.fld:focus { outline: none; border-color: #3DAFB9; box-shadow: 0 0 0 3px rgba(61,175,185,0.15); }
</style>

<x-filament-panels::page>
    <div class="rw-inbox" wire:poll.5s dir="rtl">
        {{-- ═════════ Two-pane layout ═════════ --}}
        <div class="rw-inbox-wrap">
            {{-- LEFT: conversation list --}}
            <aside class="rw-inbox-list">
                <div class="rw-inbox-list-head">
                    <div>
                        <div class="rw-inbox-list-title">
                            <span class="rw-inbox-title-dot"></span>
                            المحادثات النشطة
                        </div>
                        <div class="rw-inbox-list-count">{{ $conversations->count() }} محادثة</div>
                    </div>
                </div>

                @if($conversations->isEmpty())
                    <div class="rw-inbox-empty">
                        <div class="rw-inbox-empty-icon">💬</div>
                        <div class="rw-inbox-empty-text">لا توجد محادثات نشطة الآن.</div>
                    </div>
                @else
                    <div class="rw-inbox-scroll">
                        @foreach($conversations as $c)
                            @php
                                $isSelected = $selected && $selected->id === $c->id;
                                $unread = $c->messages()->where('sender_type', 'user')->whereNull('read_at')->count();
                                $latest = $c->latestMessage;
                                $isOpen = $c->status === 'open';
                            @endphp
                            <button type="button" wire:click="selectConversation({{ $c->id }})"
                                class="rw-inbox-item {{ $isSelected ? 'is-selected' : '' }} {{ $isOpen ? 'is-open' : '' }}">
                                <div class="rw-inbox-item-avatar">
                                    {{ mb_substr($c->user?->name ?? '؟', 0, 1) }}
                                    @if($unread > 0)
                                        <span class="rw-inbox-item-badge">{{ $unread }}</span>
                                    @endif
                                </div>
                                <div class="rw-inbox-item-body">
                                    <div class="rw-inbox-item-top">
                                        <span class="rw-inbox-item-name">{{ $c->user?->name ?? 'عميل' }}</span>
                                        <span class="rw-inbox-item-time">{{ $c->last_message_at?->diffForHumans(short: true) }}</span>
                                    </div>
                                    <div class="rw-inbox-item-preview">
                                        @if($latest)
                                            @if($latest->sender_type === 'user')
                                                {{ \Illuminate\Support\Str::limit($latest->body, 50) }}
                                            @elseif($latest->sender_type === 'consultant')
                                                <span style="opacity:.7">أنت: </span>{{ \Illuminate\Support\Str::limit($latest->body, 45) }}
                                            @else
                                                <span style="font-style:italic;opacity:.7">{{ \Illuminate\Support\Str::limit($latest->body, 45) }}</span>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="rw-inbox-item-status">
                                        @if($isOpen)
                                            <span class="rw-inbox-status rw-inbox-status--open">
                                                <span class="rw-inbox-status-dot"></span>
                                                @if($c->assigned_consultant_id) مُعيَّنة لك @else متاحة — أنت أوّل من يردّ @endif
                                            </span>
                                        @else
                                            <span class="rw-inbox-status rw-inbox-status--assigned">
                                                {{ $c->consultant?->full_name_ar ?? 'مسنَدة' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                @endif
            </aside>

            {{-- RIGHT: message view ═════════ --}}
            <main class="rw-inbox-main">
                @if(! $selected)
                    <div class="rw-inbox-placeholder">
                        <div class="rw-inbox-placeholder-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <div class="rw-inbox-placeholder-title">اختر محادثة للبدء</div>
                        <div class="rw-inbox-placeholder-sub">اختر أي محادثة من القائمة اليمنى لعرضها والرد عليها.</div>
                    </div>
                @else
                    {{-- Header --}}
                    <div class="rw-inbox-thread-head">
                        <div class="rw-inbox-thread-user">
                            <div class="rw-inbox-thread-avatar">
                                {{ mb_substr($selected->user?->name ?? '؟', 0, 1) }}
                            </div>
                            <div>
                                <div class="rw-inbox-thread-name">{{ $selected->user?->name }}</div>
                                <div class="rw-inbox-thread-email" dir="ltr">{{ $selected->user?->email }}</div>
                            </div>
                        </div>
                        <div class="rw-inbox-thread-actions">
                            @if($selected->status !== 'closed')
                                <button type="button" wire:click="closeConversation" wire:confirm="إغلاق المحادثة نهائياً؟"
                                    class="rw-inbox-close-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    إغلاق المحادثة
                                </button>
                            @else
                                <span class="rw-inbox-closed-tag">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    مغلقة
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Messages --}}
                    <div class="rw-inbox-thread" id="rwInboxThread">
                        @foreach($selected->messages as $m)
                            @php
                                $isMine = ($m->sender_type === 'consultant' && $currentUser->consultant && $m->sender_id === $currentUser->consultant?->id)
                                    || ($m->sender_type === 'system' && $currentUser->role === 'admin');
                            @endphp
                            <div class="rw-inbox-msg {{ $m->sender_type === 'system' ? 'rw-inbox-msg--system' : ($isMine ? 'rw-inbox-msg--mine' : 'rw-inbox-msg--theirs') }}">
                                <div class="rw-inbox-msg-bubble">
                                    <div>{{ $m->body }}</div>
                                    <div class="rw-inbox-msg-time">{{ $m->created_at->format('H:i · Y-m-d') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Reply --}}
                    @if($selected->status !== 'closed')
                        <form wire:submit.prevent="sendReply" class="rw-inbox-reply">
                            <textarea wire:model.defer="reply" placeholder="اكتب ردّك..." rows="2" class="rw-inbox-reply-input" required></textarea>
                            <button type="submit" class="rw-inbox-reply-send">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                                إرسال
                            </button>
                        </form>
                    @endif
                @endif
            </main>
        </div>
    </div>

    <style>
        .rw-inbox { --ink:#0F172A; --sub:#64748B; --bg:#FFFFFF; --line:rgba(15,23,42,.08); --brand-teal:#3DAFB9; --brand-navy:#2D4B7E; }
        .dark .rw-inbox { --ink:#F1F5F9; --sub:#94A3B8; --bg:rgba(18,36,64,.65); --line:rgba(107,200,210,.12); }

        .rw-inbox-wrap { display:grid; grid-template-columns:340px 1fr; gap:16px; height:calc(100vh - 200px); min-height:520px; }
        @media (max-width:900px){ .rw-inbox-wrap { grid-template-columns:1fr; height:auto; } }

        /* LEFT: list */
        .rw-inbox-list { background:var(--bg); border:1px solid var(--line); border-radius:18px; overflow:hidden; display:flex; flex-direction:column; }
        .rw-inbox-list-head { padding:16px 18px; border-bottom:1px solid var(--line); }
        .rw-inbox-list-title { display:inline-flex; align-items:center; gap:8px; font-size:14px; font-weight:800; color:var(--ink); }
        .rw-inbox-title-dot { width:8px; height:8px; border-radius:50%; background:#10B981; box-shadow:0 0 0 3px rgba(16,185,129,.2); }
        .rw-inbox-list-count { font-size:11px; color:var(--sub); margin-top:2px; }
        .rw-inbox-scroll { flex:1; overflow-y:auto; }
        .rw-inbox-scroll::-webkit-scrollbar { width:6px; }
        .rw-inbox-scroll::-webkit-scrollbar-thumb { background:rgba(61,175,185,.2); border-radius:3px; }

        .rw-inbox-item { display:flex; gap:12px; padding:14px 16px; border-bottom:1px solid var(--line); background:transparent; border-inline-start:3px solid transparent; text-align:start; cursor:pointer; transition:all 200ms; font-family:inherit; width:100%; }
        .rw-inbox-item:hover { background:rgba(61,175,185,.04); }
        .rw-inbox-item.is-selected { background:linear-gradient(90deg, rgba(61,175,185,.08), transparent); border-inline-start-color:#3DAFB9; }
        .rw-inbox-item.is-open .rw-inbox-item-avatar::after { content:''; position:absolute; top:-2px; inset-inline-end:-2px; width:12px; height:12px; border-radius:50%; background:#F59E0B; border:2px solid var(--bg); animation:rw-inbox-blink 1.5s infinite; }
        @keyframes rw-inbox-blink { 0%,100%{opacity:1;} 50%{opacity:.4;} }

        .rw-inbox-item-avatar { position:relative; flex-shrink:0; width:44px; height:44px; border-radius:12px; background:linear-gradient(135deg,#2D4B7E,#3DAFB9); color:white; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:16px; }
        .rw-inbox-item-badge { position:absolute; bottom:-4px; inset-inline-end:-4px; min-width:20px; height:20px; padding:0 6px; border-radius:999px; background:#EF4444; color:white; font-size:10px; font-weight:800; display:inline-flex; align-items:center; justify-content:center; }
        .rw-inbox-item-body { flex:1; min-width:0; }
        .rw-inbox-item-top { display:flex; justify-content:space-between; align-items:baseline; gap:8px; margin-bottom:3px; }
        .rw-inbox-item-name { font-size:13.5px; font-weight:800; color:var(--ink); }
        .rw-inbox-item-time { font-size:10px; color:var(--sub); font-variant-numeric:tabular-nums; flex-shrink:0; }
        .rw-inbox-item-preview { font-size:12px; color:var(--sub); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-bottom:4px; }
        .rw-inbox-item-status { font-size:10px; }
        .rw-inbox-status { display:inline-flex; align-items:center; gap:4px; padding:2px 8px; border-radius:999px; font-weight:800; letter-spacing:0.02em; }
        .rw-inbox-status--open { background:rgba(245,158,11,.1); color:#D97706; }
        .rw-inbox-status--assigned { background:rgba(16,185,129,.1); color:#059669; }
        .rw-inbox-status-dot { width:5px; height:5px; border-radius:50%; background:currentColor; }

        /* RIGHT: main */
        .rw-inbox-main { background:var(--bg); border:1px solid var(--line); border-radius:18px; overflow:hidden; display:flex; flex-direction:column; }
        .rw-inbox-placeholder { flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:40px; text-align:center; color:var(--sub); }
        .rw-inbox-placeholder-icon { width:80px; height:80px; border-radius:24px; background:linear-gradient(135deg,rgba(61,175,185,.1),rgba(45,75,126,.05)); color:var(--brand-teal); display:inline-flex; align-items:center; justify-content:center; margin-bottom:16px; }
        .rw-inbox-placeholder-icon svg { width:40px; height:40px; }
        .rw-inbox-placeholder-title { font-size:17px; font-weight:900; color:var(--ink); margin-bottom:6px; }
        .rw-inbox-placeholder-sub { font-size:13px; }

        .rw-inbox-thread-head { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:16px 20px; border-bottom:1px solid var(--line); background:linear-gradient(180deg, rgba(61,175,185,.03), transparent); }
        .rw-inbox-thread-user { display:flex; align-items:center; gap:12px; min-width:0; }
        .rw-inbox-thread-avatar { flex-shrink:0; width:40px; height:40px; border-radius:12px; background:linear-gradient(135deg,#2D4B7E,#3DAFB9); color:white; display:inline-flex; align-items:center; justify-content:center; font-weight:900; font-size:15px; }
        .rw-inbox-thread-name { font-size:14px; font-weight:800; color:var(--ink); }
        .rw-inbox-thread-email { font-size:11px; color:var(--sub); margin-top:2px; }

        .rw-inbox-close-btn { display:inline-flex; align-items:center; gap:6px; padding:8px 14px; border-radius:999px; background:rgba(239,68,68,.1); color:#DC2626; border:0; font-size:11.5px; font-weight:800; cursor:pointer; transition:all 200ms; font-family:inherit; }
        .rw-inbox-close-btn:hover { background:rgba(239,68,68,.15); }
        .rw-inbox-close-btn svg { width:13px; height:13px; }
        .rw-inbox-closed-tag { display:inline-flex; align-items:center; gap:6px; padding:6px 12px; border-radius:999px; background:rgba(16,185,129,.1); color:#059669; font-size:11px; font-weight:800; }
        .rw-inbox-closed-tag svg { width:14px; height:14px; }

        /* Thread */
        .rw-inbox-thread { flex:1; overflow-y:auto; padding:20px; display:flex; flex-direction:column; gap:10px; background:linear-gradient(180deg,#FAFCFD,#F0F4FA); }
        .dark .rw-inbox-thread { background:linear-gradient(180deg, rgba(10,23,41,.4), rgba(18,36,64,.4)); }
        .rw-inbox-msg { display:flex; }
        .rw-inbox-msg--mine { justify-content:flex-end; }
        .rw-inbox-msg--theirs { justify-content:flex-start; }
        .rw-inbox-msg--system { justify-content:center; }
        .rw-inbox-msg-bubble { max-width:70%; padding:12px 16px; border-radius:16px; font-size:13.5px; line-height:1.75; box-shadow:0 2px 6px -2px rgba(15,23,42,.05); }
        .rw-inbox-msg--mine .rw-inbox-msg-bubble { background:linear-gradient(135deg,#2D4B7E,#3DAFB9); color:white; border-bottom-inline-end-radius:4px; }
        .rw-inbox-msg--theirs .rw-inbox-msg-bubble { background:white; color:var(--ink); border:1px solid rgba(61,175,185,.15); border-bottom-inline-start-radius:4px; }
        .dark .rw-inbox-msg--theirs .rw-inbox-msg-bubble { background:#1E3A5F; }
        .rw-inbox-msg--system .rw-inbox-msg-bubble { max-width:80%; background:linear-gradient(135deg,rgba(61,175,185,.08),rgba(45,75,126,.04)); border:1px solid rgba(61,175,185,.2); color:#475569; text-align:center; font-size:12px; border-radius:12px; }
        .rw-inbox-msg-time { font-size:10px; opacity:.6; margin-top:5px; font-variant-numeric:tabular-nums; }

        /* Reply */
        .rw-inbox-reply { display:flex; gap:10px; padding:14px 18px; border-top:1px solid var(--line); background:var(--bg); align-items:flex-end; }
        .rw-inbox-reply-input { flex:1; min-height:44px; max-height:120px; padding:12px 16px; border-radius:14px; border:1px solid rgba(15,23,42,.1); background:#F8FAFC; font-size:13.5px; font-family:inherit; color:var(--ink); outline:none; resize:none; transition:all 200ms; }
        .dark .rw-inbox-reply-input { background:#0F2340; color:#F1F5F9; border-color:rgba(107,200,210,.15); }
        .rw-inbox-reply-input:focus { border-color:var(--brand-teal); box-shadow:0 0 0 3px rgba(61,175,185,.15); background:white; }
        .dark .rw-inbox-reply-input:focus { background:#15294D; }
        .rw-inbox-reply-send { flex-shrink:0; display:inline-flex; align-items:center; gap:6px; padding:0 20px; height:44px; border-radius:14px; border:0; background:linear-gradient(135deg,#2D4B7E,#3DAFB9); color:white; font-size:13px; font-weight:800; cursor:pointer; transition:all 200ms; font-family:inherit; box-shadow:0 6px 16px -4px rgba(61,175,185,.4); }
        .rw-inbox-reply-send:hover { transform:translateY(-1px); box-shadow:0 10px 22px -6px rgba(61,175,185,.55); }
        .rw-inbox-reply-send svg { width:16px; height:16px; transform:scaleX(-1); }
        [dir="rtl"] .rw-inbox-reply-send svg { transform:none; }

        .rw-inbox-empty { padding:40px 20px; text-align:center; color:var(--sub); }
        .rw-inbox-empty-icon { font-size:40px; opacity:.4; margin-bottom:12px; }
        .rw-inbox-empty-text { font-size:13px; }
    </style>

    <script>
        // Auto-scroll thread to bottom on load / Livewire update
        document.addEventListener('livewire:navigated', scrollThread);
        document.addEventListener('livewire:load', scrollThread);
        window.addEventListener('load', scrollThread);
        function scrollThread() {
            const t = document.getElementById('rwInboxThread');
            if (t) t.scrollTop = t.scrollHeight;
        }
        // Also after any Livewire refresh
        if (window.Livewire) {
            window.Livewire.hook('message.processed', scrollThread);
        }
    </script>
</x-filament-panels::page>

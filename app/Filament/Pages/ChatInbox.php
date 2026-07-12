<?php

namespace App\Filament\Pages;

use App\Models\Conversation;
use App\Models\Message;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ChatInbox extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'صندوق المحادثات';
    protected static ?string $navigationGroup = 'العمليات';
    protected static ?int    $navigationSort  = 5;
    protected static string  $view            = 'filament.pages.chat-inbox';
    protected static ?string $title           = 'المحادثات المباشرة مع العملاء';
    protected static ?string $pollingInterval = '5s';

    public ?int $selectedId = null;
    public string $reply = '';

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'consultant']);
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::conversationsQuery()->open()->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::conversationsQuery()->open()->exists() ? 'warning' : null;
    }

    protected static function conversationsQuery()
    {
        $user = auth()->user();
        $q = Conversation::query()->active();
        if ($user?->role === 'consultant') {
            // Consultant sees: unassigned convos (available to claim) + their assigned ones
            $q->where(function ($sub) use ($user) {
                $sub->whereNull('assigned_consultant_id')
                    ->orWhere('assigned_consultant_id', $user->consultant?->id);
            });
        }
        return $q;
    }

    public function getViewData(): array
    {
        $conversations = static::conversationsQuery()
            ->with(['user', 'consultant.user', 'latestMessage'])
            ->orderByDesc('last_message_at')
            ->limit(50)
            ->get();

        $selected = null;
        if ($this->selectedId) {
            $selected = Conversation::with(['user', 'consultant.user', 'messages'])->find($this->selectedId);
        }

        return [
            'conversations' => $conversations,
            'selected'      => $selected,
            'currentUser'   => auth()->user(),
        ];
    }

    public function selectConversation(int $id): void
    {
        $this->selectedId = $id;
        $this->reply = '';

        // Mark other-side messages as read
        $conv = Conversation::find($id);
        if ($conv) {
            $conv->messages()
                ->where('sender_type', Message::SENDER_USER)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }
    }

    public function sendReply(): void
    {
        $user = auth()->user();
        if (! $this->selectedId || ! trim($this->reply)) return;

        $conv = Conversation::find($this->selectedId);
        if (! $conv || $conv->status === Conversation::STATUS_CLOSED) return;

        $consultantId = null;
        if ($user->role === 'consultant' && $user->consultant) {
            // Claim if unassigned
            if (! $conv->assigned_consultant_id) {
                $conv->claim($user->consultant);
            }
            if ($conv->assigned_consultant_id !== $user->consultant->id) {
                Notification::make()->title('تم تعيين المحادثة لمستشار آخر')->warning()->send();
                return;
            }
            $consultantId = $user->consultant->id;
        }

        Message::create([
            'conversation_id' => $conv->id,
            'sender_type'     => $user->role === 'consultant'
                ? Message::SENDER_CONSULTANT
                : Message::SENDER_SYSTEM, // admin replies as system
            'sender_id'       => $consultantId,
            'body'            => trim($this->reply),
        ]);

        $conv->update(['last_message_at' => now()]);
        $this->reply = '';
        Notification::make()->title('تم إرسال الرد')->success()->duration(1500)->send();
    }

    public function closeConversation(): void
    {
        if (! $this->selectedId) return;
        Conversation::where('id', $this->selectedId)->update([
            'status'    => Conversation::STATUS_CLOSED,
            'closed_at' => now(),
        ]);
        $this->selectedId = null;
        Notification::make()->title('تم إغلاق المحادثة')->success()->send();
    }
}

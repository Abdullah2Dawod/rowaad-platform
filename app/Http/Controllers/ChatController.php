<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Start (or return existing active) conversation for the authenticated user.
     * Sends a welcome system message on creation.
     */
    public function start(Request $request): JsonResponse
    {
        $user = $request->user();
        abort_unless($user, 401, 'يجب تسجيل الدخول لبدء المحادثة.');

        // Reuse an active conversation if the user has one
        $conv = Conversation::where('user_id', $user->id)->active()->latest()->first();

        if (! $conv) {
            $conv = Conversation::create([
                'user_id'         => $user->id,
                'status'          => Conversation::STATUS_OPEN,
                'last_message_at' => now(),
            ]);
            Message::create([
                'conversation_id' => $conv->id,
                'sender_type'     => Message::SENDER_SYSTEM,
                'body'            => "أهلاً بك في رواد بلا حدود! 👋\nسيتم توصيلك مع أحد مستشارينا خلال لحظات — اكتب سؤالك وسنردّ عليك في أسرع وقت.",
            ]);
        }

        return response()->json([
            'conversation' => $this->serializeConversation($conv->fresh(['messages', 'consultant.user'])),
        ]);
    }

    /**
     * Fetch messages for a conversation (for polling).
     */
    public function messages(Request $request, Conversation $conversation): JsonResponse
    {
        abort_unless($this->canAccess($request->user(), $conversation), 403);

        $conversation->load(['messages', 'consultant.user']);

        // Mark other-side messages as read
        $senderTypeToRead = $request->user()->role === 'user' ? Message::SENDER_CONSULTANT : Message::SENDER_USER;
        $conversation->messages()
            ->where('sender_type', $senderTypeToRead)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'conversation' => $this->serializeConversation($conversation),
        ]);
    }

    /**
     * User sends a new message.
     */
    public function send(Request $request, Conversation $conversation): JsonResponse
    {
        $user = $request->user();
        abort_unless($this->canAccess($user, $conversation), 403);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        // Determine sender type based on role
        $senderType = $user->role === 'consultant' ? Message::SENDER_CONSULTANT : Message::SENDER_USER;
        $senderId   = $user->role === 'consultant' ? $user->consultant?->id : $user->id;

        // If a consultant is replying and conversation is unassigned → claim it (first-reply wins)
        if ($senderType === Message::SENDER_CONSULTANT && ! $conversation->assigned_consultant_id && $user->consultant) {
            $conversation->claim($user->consultant);
        }

        // Guard: after assignment, only the assigned consultant can reply
        if ($senderType === Message::SENDER_CONSULTANT
            && $conversation->assigned_consultant_id !== $user->consultant?->id) {
            abort(403, 'تم تعيين هذه المحادثة لمستشار آخر.');
        }

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_type'     => $senderType,
            'sender_id'       => $senderId,
            'body'            => $data['body'],
        ]);

        $conversation->update(['last_message_at' => now()]);

        return response()->json([
            'conversation' => $this->serializeConversation($conversation->fresh(['messages', 'consultant.user'])),
        ]);
    }

    /**
     * Close a conversation (user or assigned consultant only).
     */
    public function close(Request $request, Conversation $conversation): JsonResponse
    {
        abort_unless($this->canAccess($request->user(), $conversation), 403);

        $conversation->update([
            'status'    => Conversation::STATUS_CLOSED,
            'closed_at' => now(),
        ]);

        return response()->json(['ok' => true]);
    }

    // ─── helpers ───────────────────────────────────────────────

    private function canAccess($user, Conversation $conversation): bool
    {
        if (! $user) return false;
        if ($user->role === 'admin') return true;
        if ($user->id === $conversation->user_id) return true;
        if ($user->role === 'consultant' && $user->consultant) {
            // A consultant can access if unassigned (to claim) or already the assignee
            return ! $conversation->assigned_consultant_id
                || $conversation->assigned_consultant_id === $user->consultant->id;
        }
        return false;
    }

    private function serializeConversation(Conversation $c): array
    {
        return [
            'id'         => $c->id,
            'status'     => $c->status,
            'consultant' => $c->consultant ? [
                'id'     => $c->consultant->id,
                'name'   => $c->consultant->full_name_ar ?? $c->consultant->user?->name,
                'title'  => $c->consultant->professional_title,
                'avatar' => $c->consultant->avatar_url,
            ] : null,
            'messages'   => $c->messages->map(fn ($m) => [
                'id'          => $m->id,
                'sender_type' => $m->sender_type,
                'body'        => $m->body,
                'is_mine'     => $this->isMineForCurrentUser($m),
                'time'        => $m->created_at->format('H:i'),
                'created_at'  => $m->created_at->toIso8601String(),
            ])->values(),
        ];
    }

    private function isMineForCurrentUser(Message $m): bool
    {
        $u = request()->user();
        if (! $u) return false;
        if ($m->sender_type === Message::SENDER_USER && $u->role === 'user'  && $m->sender_id === $u->id) return true;
        if ($m->sender_type === Message::SENDER_CONSULTANT && $u->consultant && $m->sender_id === $u->consultant->id) return true;
        return false;
    }
}

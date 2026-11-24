<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    /**
     * Display inbox list of conversations.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $conversations = Conversation::where('tenant_id', $user->id)
            ->orWhere('owner_id', $user->id)
            ->with(['property', 'tenant', 'owner', 'latestMessage.sender'])
            ->orderByDesc('last_message_at')
            ->get();

        $activeConversation = $conversations->first();

        return view('chat.index', compact('conversations', 'activeConversation'));
    }

    /**
     * Show a specific conversation thread.
     */
    public function show(Request $request, Conversation $conversation): View
    {
        $user = $request->user();

        // Check authorization
        if ($conversation->tenant_id !== $user->id && $conversation->owner_id !== $user->id && ! $user->isAdmin()) {
            abort(403, 'Akses obrolan tidak diizinkan.');
        }

        // Mark incoming unread messages as read
        $conversation->messages()
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $conversations = Conversation::where('tenant_id', $user->id)
            ->orWhere('owner_id', $user->id)
            ->with(['property', 'tenant', 'owner', 'latestMessage.sender'])
            ->orderByDesc('last_message_at')
            ->get();

        $conversation->load(['property', 'tenant', 'owner', 'messages.sender']);

        return view('chat.index', [
            'conversations' => $conversations,
            'activeConversation' => $conversation,
        ]);
    }

    /**
     * Start a conversation about a property.
     */
    public function start(Request $request, Property $property): RedirectResponse
    {
        $user = $request->user();

        // Can't chat with self
        if ($property->user_id === $user->id) {
            return back()->with('error', 'Anda tidak dapat mengirim pesan ke properti milik sendiri.');
        }

        // Find existing conversation or create new
        $conversation = Conversation::firstOrCreate(
            [
                'property_id' => $property->id,
                'tenant_id' => $user->id,
                'owner_id' => $property->user_id,
            ],
            [
                'last_message_at' => now(),
            ]
        );

        // If message body was passed, send initial message
        if ($request->filled('initial_message')) {
            $msg = $request->input('initial_message');
            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'message' => $msg,
            ]);
            $conversation->update(['last_message_at' => now()]);
        }

        return redirect()->route('chat.show', $conversation);
    }

    /**
     * Send a message in a conversation.
     */
    public function send(Request $request, Conversation $conversation): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        if ($conversation->tenant_id !== $user->id && $conversation->owner_id !== $user->id && ! $user->isAdmin()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $validated = $request->validate([
            'message' => 'required|string|max:3000',
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'message' => trim($validated['message']),
            'is_read' => false,
        ]);

        $conversation->update(['last_message_at' => now()]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'text' => $message->message,
                    'sender_id' => $message->sender_id,
                    'sender_name' => $user->name,
                    'time' => $message->created_at->format('H:i'),
                    'is_me' => true,
                ],
            ]);
        }

        return redirect()->route('chat.show', $conversation);
    }

    /**
     * API: Get messages for real-time polling in Alpine.js.
     */
    public function getMessages(Request $request, Conversation $conversation): JsonResponse
    {
        $user = $request->user();

        if ($conversation->tenant_id !== $user->id && $conversation->owner_id !== $user->id && ! $user->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Mark incoming unread messages as read
        $conversation->messages()
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) use ($user) {
                return [
                    'id' => $msg->id,
                    'text' => $msg->message,
                    'sender_id' => $msg->sender_id,
                    'sender_name' => $msg->sender->name,
                    'is_me' => $msg->sender_id === $user->id,
                    'time' => $msg->created_at->format('H:i'),
                    'date' => $msg->created_at->isoFormat('D MMM Y'),
                ];
            });

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }
}

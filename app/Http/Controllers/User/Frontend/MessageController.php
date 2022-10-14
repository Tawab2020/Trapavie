<?php

namespace App\Http\Controllers\User\Frontend;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\MessageRequest;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;


class MessageController extends Controller
{
    public function conversations()
    {
        $user = Auth('user')->user();
        $conversations = $user->conversations;
        $conversations->load(['users', 'messages']);
        return response()->json($conversations, 200);
    }

    public function createConversation(Request $request)
    {
        $conversation = Conversation::create();
        $conversation->users()->sync([$request->input('users')[0] => ['type' => $request->input('senderType')], $request->input('users')[1] => ['type' => $request->input('receiverType')]]);
        $user = Auth('user')->user();
        // loading with user pivot table (needed in frontend)
        $conversation = $user->conversations()->where('conversations.id', $conversation->id)->first();
        $conversation->load(['users', 'messages']);
        return response()->json($conversation, 200);
    }

    public function storeMessage(MessageRequest $request, User $user)
    {
        $message_data = $request->validated();
        $message = Message::create($message_data);
        event(new MessageSent($message));
        return response()->json($message, 200);
    }

    public function messages(Conversation $conversation)
    {
        $messages = $conversation->messages;
        return response()->json($messages, 200);
    }
}

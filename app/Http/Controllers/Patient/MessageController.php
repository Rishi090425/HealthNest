<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;
use App\Models\User;

class MessageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $conversations = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with('sender', 'receiver')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($m) => $m->sender_id === $user->id ? $m->receiver : $m->sender)
            ->unique('id')
            ->values();
        return view('patient.messages.index', compact('conversations'));
    }

    public function show(User $user)
    {
        $me = auth()->user();
        $messages = Message::where(function ($q) use ($me, $user) {
            $q->where('sender_id', $me->id)->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($me, $user) {
            $q->where('sender_id', $user->id)->where('receiver_id', $me->id);
        })->orderBy('created_at')->get();

        Message::where('sender_id', $user->id)
            ->where('receiver_id', $me->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('patient.messages.show', compact('messages', 'user'));
    }

    public function store(StoreMessageRequest $request)
    {
        Message::create(array_merge($request->validated(), ['sender_id' => auth()->id()]));
        return back()->with('success', 'Message sent.');
    }
}

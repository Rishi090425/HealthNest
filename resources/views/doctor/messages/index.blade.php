@extends('layouts.app')
@section('title', 'Messages')
@section('page-title', 'Messages')
@section('content')
    @php
        $currentUser = isset($user) ? $user : null;
    @endphp
    @include('patient.messages.index', ['conversations' => $conversations, 'messages' => $messages ?? null, 'user' => $user ?? null, 'currentUser' => $currentUser])
@endsection

@section('scripts')
<script>
const box = document.getElementById('message-box');
if (box) box.scrollTop = box.scrollHeight;
</script>
@endsection

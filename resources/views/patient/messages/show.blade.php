@extends('layouts.app')
@section('title', 'Messages — ' . $user->name)
@section('page-title', 'Messages')

@section('content')
    @php $conversations = collect([$user]); @endphp
    @include('patient.messages.index', ['messages' => $messages, 'user' => $user, 'conversations' => $conversations, 'currentUser' => $user])
@endsection

@extends('layouts.app')
@section('title', 'Messages — ' . $user->name)
@section('page-title', 'Messages')
@section('content')
    @include('doctor.messages.index', ['conversations' => collect([$user]), 'messages' => $messages, 'user' => $user, 'currentUser' => $user])
@endsection

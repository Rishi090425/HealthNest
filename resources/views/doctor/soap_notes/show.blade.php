@extends('layouts.app')
@section('title', 'SOAP Note — ' . $soapNote->patient->user->name)
@section('page-title', 'SOAP Note')
@section('content')
<div class="max-w-3xl mx-auto space-y-4">
    <div class="flex justify-between items-center">
        <div>
            <p class="font-semibold text-gray-900 dark:text-white">{{ $soapNote->patient->user->name }}</p>
            <p class="text-sm text-gray-500">{{ $soapNote->created_at->format('d M Y, H:i') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('doctor.soap-notes.edit', $soapNote) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm px-4 py-2 rounded-lg transition-colors"><i class="fas fa-edit mr-1"></i>Edit</a>
            <a href="{{ route('doctor.soap-notes.index') }}" class="border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-400 text-sm px-4 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Back</a>
        </div>
    </div>

    @foreach(['subjective' => ['S', 'Subjective', 'blue'], 'objective' => ['O', 'Objective', 'green'], 'assessment' => ['A', 'Assessment', 'yellow'], 'plan' => ['P', 'Plan', 'purple']] as $field => [$letter, $label, $color])
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-8 h-8 rounded-lg bg-{{ $color }}-100 dark:bg-{{ $color }}-900/40 flex items-center justify-center font-bold text-{{ $color }}-700 dark:text-{{ $color }}-300 text-sm">
                {{ $letter }}
            </div>
            <h3 class="font-semibold text-gray-800 dark:text-gray-100">{{ $label }}</h3>
        </div>
        <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed whitespace-pre-wrap">
            {{ $soapNote->$field ?? 'Not recorded.' }}
        </p>
    </div>
    @endforeach

    @if($soapNote->appointment)
    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-4 text-sm text-gray-500">
        <i class="fas fa-calendar-check mr-2"></i>Linked to appointment on {{ $soapNote->appointment->appointment_date->format('d M Y') }}
    </div>
    @endif
</div>
@endsection

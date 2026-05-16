@extends('layouts.app')
@section('title', 'Submit Request')
@section('page-title', 'Submit a Request')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-comment-alt text-purple-600"></i>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-800">New Request / Complaint</h2>
                <p class="text-sm text-gray-500">We'll respond within 24–48 hours</p>
            </div>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-6">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('patient.complaints.store') }}" class="space-y-5">
            @csrf

            {{-- Type --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Request Type *</label>
                <div class="grid grid-cols-3 gap-3">
                    @foreach([
                        ['value'=>'complaint',      'icon'=>'fas fa-exclamation-triangle', 'label'=>'Complaint',       'color'=>'red'],
                        ['value'=>'change_request', 'icon'=>'fas fa-edit',                 'label'=>'Change Request',  'color'=>'blue'],
                        ['value'=>'feedback',       'icon'=>'fas fa-star',                 'label'=>'Feedback',        'color'=>'yellow'],
                    ] as $type)
                        <label class="flex flex-col items-center gap-2 p-3 border-2 rounded-xl cursor-pointer transition-all has-[:checked]:border-{{ $type['color'] }}-500 has-[:checked]:bg-{{ $type['color'] }}-50 border-gray-100 hover:border-gray-300 text-center">
                            <input type="radio" name="type" value="{{ $type['value'] }}" class="sr-only" {{ old('type', 'complaint') === $type['value'] ? 'checked' : '' }}>
                            <i class="{{ $type['icon'] }} text-{{ $type['color'] }}-500 text-xl"></i>
                            <span class="text-xs font-medium text-gray-700">{{ $type['label'] }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Subject *</label>
                <input type="text" name="subject" value="{{ old('subject') }}" required maxlength="255"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Brief subject of your request...">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Details *</label>
                <textarea name="message" rows="5" required maxlength="2000"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                    placeholder="Describe your issue, request, or feedback in detail...">{{ old('message') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Max 2000 characters</p>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" id="submit-complaint-btn"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg text-sm transition-colors">
                    <i class="fas fa-paper-plane mr-2"></i>Submit Request
                </button>
                <a href="{{ route('patient.complaints.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">Cancel</a>
            </div>
        </form>
    </div>

    {{-- Info Note --}}
    <div class="mt-4 bg-gray-50 border border-gray-200 rounded-xl p-4 flex items-start gap-3">
        <i class="fas fa-shield-alt text-blue-400 mt-0.5 flex-shrink-0"></i>
        <div class="text-sm text-gray-600">
            Your submission is confidential and will only be reviewed by the healthcare portal administration team.
            For <strong>medical emergencies</strong>, please call <strong class="text-red-600">108</strong> immediately.
        </div>
    </div>
</div>
@endsection

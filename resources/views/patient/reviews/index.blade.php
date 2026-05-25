@extends('layouts.app')
@section('title', 'My Reviews')
@section('page-title', 'My Reviews')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <p class="text-sm text-gray-500">Rate your experience with doctors after completed consultations.</p>
        <a href="{{ route('patient.reviews.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-star mr-2"></i>Write a Review
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($reviews as $review)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/40 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-md text-primary-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $review->doctor->user->display_name }}</p>
                        <p class="text-xs text-gray-500">{{ $review->appointment?->appointment_date?->format('d M Y') }}</p>
                    </div>
                </div>
                <div class="flex gap-0.5">
                    @for($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star text-sm {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200 dark:text-gray-600' }}"></i>
                    @endfor
                </div>
            </div>
            @if($review->comment)
            <p class="text-sm text-gray-600 dark:text-gray-400 italic">"{{ $review->comment }}"</p>
            @endif
            <p class="text-xs text-gray-400 mt-3">{{ $review->created_at->diffForHumans() }}</p>
        </div>
        @empty
        <div class="col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-12 text-center">
            <i class="fas fa-star text-4xl text-gray-200 dark:text-gray-600 mb-4"></i>
            <p class="text-gray-500">You haven't written any reviews yet.</p>
            <a href="{{ route('patient.reviews.create') }}" class="mt-4 inline-block bg-primary-600 text-white text-sm px-5 py-2 rounded-lg">Write your first review</a>
        </div>
        @endforelse
    </div>
    <div>{{ $reviews->links() }}</div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Write a Review')
@section('page-title', 'Write a Review')
@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
        <h2 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-5">Rate Your Experience</h2>

        @if($appointments->isEmpty())
        <div class="text-center py-8">
            <i class="fas fa-calendar-times text-4xl text-gray-200 dark:text-gray-600 mb-3"></i>
            <p class="text-gray-500 text-sm">No completed appointments available for review.</p>
            <a href="{{ route('patient.appointments.index') }}" class="mt-3 inline-block text-primary-600 text-sm hover:underline">View my appointments</a>
        </div>
        @else
        <form method="POST" action="{{ route('patient.reviews.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Appointment</label>
                <select name="appointment_id" id="appointment_id" required
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    <option value="">Choose an appointment...</option>
                    @foreach($appointments as $appt)
                    <option value="{{ $appt->id }}" data-doctor="{{ $appt->doctor_id }}">
                        Dr. {{ $appt->doctor->user->name }} — {{ $appt->appointment_date->format('d M Y') }}
                    </option>
                    @endforeach
                </select>
                <input type="hidden" name="doctor_id" id="doctor_id">
                @error('appointment_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rating</label>
                <div class="flex gap-2" id="star-rating">
                    @for($i = 1; $i <= 5; $i++)
                    <button type="button" data-value="{{ $i }}"
                        class="star-btn text-3xl text-gray-200 dark:text-gray-600 hover:text-yellow-400 transition-colors focus:outline-none">
                        <i class="fas fa-star"></i>
                    </button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="rating-input" required>
                @error('rating') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Comment <span class="text-gray-400 font-normal">(optional)</span></label>
                <textarea name="comment" rows="4" placeholder="Share your experience..."
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none resize-none">{{ old('comment') }}</textarea>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('patient.reviews.index') }}" class="flex-1 text-center py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</a>
                <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 rounded-xl transition-colors text-sm">Submit Review</button>
            </div>
        </form>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
// Star rating interaction
const starBtns = document.querySelectorAll('.star-btn');
const ratingInput = document.getElementById('rating-input');

starBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        const val = parseInt(btn.dataset.value);
        ratingInput.value = val;
        starBtns.forEach((b, i) => {
            b.classList.toggle('text-yellow-400', i < val);
            b.classList.toggle('text-gray-200', i >= val);
            b.classList.toggle('dark:text-gray-600', i >= val);
        });
    });
});

// Auto-fill doctor_id from appointment selection
document.getElementById('appointment_id')?.addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    document.getElementById('doctor_id').value = selected.dataset.doctor || '';
});
</script>
@endsection

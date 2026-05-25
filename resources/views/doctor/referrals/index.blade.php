@extends('layouts.app')
@section('title', 'Referrals')
@section('page-title', 'Referrals')
@section('content')
<div class="space-y-5">
    <div class="flex justify-between items-center">
        <p class="text-sm text-gray-500">Send patients to specialist doctors with clinical notes.</p>
        <a href="{{ route('doctor.referrals.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-share-square mr-2"></i>New Referral
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">Patient</th>
                    <th class="px-4 py-3 text-left font-medium">Referred To</th>
                    <th class="px-4 py-3 text-left font-medium">Reason (Summary)</th>
                    <th class="px-4 py-3 text-left font-medium">Status</th>
                    <th class="px-4 py-3 text-left font-medium">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-700">
                @forelse($referrals as $ref)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $ref->patient->user->name }}</td>
                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $ref->referredDoctor->user->display_name }}</td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400 max-w-xs truncate">{{ $ref->reason }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $ref->status === 'accepted' ? 'bg-green-100 text-green-700' : ($ref->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ ucfirst($ref->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $ref->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No referrals sent yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">{{ $referrals->links() }}</div>
    </div>
</div>
@endsection

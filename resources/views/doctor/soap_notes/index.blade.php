@extends('layouts.app')
@section('title', 'SOAP Notes')
@section('page-title', 'SOAP Notes')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <p class="text-sm text-gray-500">Structured clinical notes for patient visits.</p>
        <a href="{{ route('doctor.soap-notes.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>New SOAP Note
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Patient</th>
                        <th class="px-4 py-3 text-left font-medium">Appointment</th>
                        <th class="px-4 py-3 text-left font-medium">Assessment (Summary)</th>
                        <th class="px-4 py-3 text-left font-medium">Created</th>
                        <th class="px-4 py-3 text-left font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-700">
                    @forelse($notes as $note)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center text-xs font-bold text-primary-700 dark:text-primary-300">
                                    {{ strtoupper(substr($note->patient->user->name, 0, 1)) }}
                                </div>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $note->patient->user->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                            {{ $note->appointment?->appointment_date?->format('d M Y') ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400 max-w-xs truncate">
                            {{ $note->assessment ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $note->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('doctor.soap-notes.show', $note) }}" class="text-primary-600 hover:text-primary-800 text-xs font-medium"><i class="fas fa-eye mr-1"></i>View</a>
                                <a href="{{ route('doctor.soap-notes.edit', $note) }}" class="text-yellow-600 hover:text-yellow-800 text-xs font-medium"><i class="fas fa-edit mr-1"></i>Edit</a>
                                <form method="POST" action="{{ route('doctor.soap-notes.destroy', $note) }}" onsubmit="return confirm('Delete this note?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs"><i class="fas fa-trash mr-1"></i>Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No SOAP notes yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">{{ $notes->links() }}</div>
    </div>
</div>
@endsection

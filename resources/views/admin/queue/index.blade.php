@extends('layouts.app')
@section('title', 'Manage Virtual Queue')
@section('page-title', 'Live Walk-in Queue')
@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($departments as $dept)
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm overflow-hidden border-b-4 border-primary-600">
            <div class="p-6 bg-slate-50 dark:bg-gray-700/50 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white">{{ $dept->name }}</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Department Queue</p>
                </div>
                <form method="POST" action="{{ route('admin.queue.call-next') }}">
                    @csrf
                    <input type="hidden" name="department_id" value="{{ $dept->id }}">
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-lg shadow-primary-200 transition-all">
                        CALL NEXT
                    </button>
                </form>
            </div>
            
            <div class="p-4">
                @php $deptQueues = $queues->get($dept->id) ?? collect(); @endphp
                @forelse($deptQueues as $q)
                <div class="flex items-center justify-between p-3 border-b dark:border-gray-700 last:border-0 {{ $q->status === 'calling' ? 'bg-primary-50 dark:bg-primary-900/10' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-white dark:bg-gray-700 shadow-sm flex items-center justify-center font-black text-sm text-primary-600">
                            #{{ $q->queue_number }}
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-900 dark:text-white">{{ $q->patient->user->name }}</p>
                            <p class="text-[10px] text-gray-400">{{ $q->created_at->format('h:i A') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold uppercase {{ $q->status === 'calling' ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ $q->status }}
                        </span>
                        <div class="flex gap-1">
                            <form method="POST" action="{{ route('admin.queue.status', $q) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="w-6 h-6 bg-green-100 text-green-700 rounded-md flex items-center justify-center text-[10px]" title="Mark Completed"><i class="fas fa-check"></i></button>
                            </form>
                            <form method="POST" action="{{ route('admin.queue.status', $q) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" class="w-6 h-6 bg-red-100 text-red-700 rounded-md flex items-center justify-center text-[10px]" title="Cancel"><i class="fas fa-times"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-xs text-gray-400 text-center py-4">No patients waiting.</p>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

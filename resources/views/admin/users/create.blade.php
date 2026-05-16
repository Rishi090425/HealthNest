@extends('layouts.app')
@section('title', isset($user) ? 'Edit User' : 'Create User Account')
@section('page-title', isset($user) ? 'Edit User' : 'Create User Account')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
        <form method="POST" action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}">
            @csrf
            @if(isset($user)) @method('PUT') @endif

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role *</label>
                    <select name="role" id="role-select" required
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        @foreach($roles as $r)
                        <option value="{{ $r }}" {{ old('role', $user->role ?? '') === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                        @endforeach
                    </select>
                    @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Password {{ isset($user) ? '(leave blank to keep current)' : '*' }}
                    </label>
                    <input type="password" name="password" {{ !isset($user) ? 'required' : '' }}
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                           class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                </div>

                @if(isset($user))
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Account Status *</label>
                    <select name="status" required class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        <option value="active"    {{ ($user->status ?? 'active') === 'active'    ? 'selected' : '' }}>Active</option>
                        <option value="inactive"  {{ ($user->status ?? '') === 'inactive'  ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ ($user->status ?? '') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
                @endif
            </div>

            {{-- Doctor-specific fields --}}
            <div id="doctor-fields" class="{{ old('role', $user->role ?? '') !== 'doctor' ? 'hidden' : '' }}">
                <div class="border-t dark:border-gray-700 pt-4 mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Doctor Profile</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Specialization</label>
                            <input type="text" name="specialization" value="{{ old('specialization', $user->doctor->specialization ?? '') }}"
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Qualification</label>
                            <input type="text" name="qualification" value="{{ old('qualification', $user->doctor->qualification ?? '') }}"
                                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <a href="{{ route('admin.users.index') }}" class="flex-1 text-center py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</a>
                <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 rounded-xl transition-colors text-sm">
                    <i class="fas fa-save mr-2"></i>{{ isset($user) ? 'Update User' : 'Create Account' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('role-select')?.addEventListener('change', function() {
    document.getElementById('doctor-fields').classList.toggle('hidden', this.value !== 'doctor');
});
</script>
@endsection

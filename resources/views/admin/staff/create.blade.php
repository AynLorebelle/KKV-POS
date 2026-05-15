<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h2 class="font-extrabold text-xl text-accent leading-tight tracking-tight">Staff Management</h2>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest bg-accent text-white shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] border-2 border-accent">
                    👑 Admin Only
                </span>
            </div>
            <a href="{{ route('dashboard') }}" class="text-sm font-bold text-accent/70 hover:text-accent underline">← Back to Dashboard</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-8">

            {{-- ── CREATE STAFF FORM ── --}}
            <div class="bg-white border-2 border-accent shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] rounded-xl p-6">
                <p class="text-[10px] font-bold uppercase tracking-widest text-accent/60 mb-1">Create Account</p>
                <h3 class="text-xl font-black text-accent mb-5">Add New Staff</h3>

                @if(session('success'))
                    <div class="bg-green-100 border-2 border-green-500 text-green-800 p-3 rounded-lg mb-4 font-bold text-sm">
                        ✅ {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 border-2 border-red-500 text-red-800 p-3 rounded-lg mb-4 font-bold text-sm">
                        ❌ {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.staff.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-accent text-[11px] font-bold uppercase tracking-widest mb-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="block w-full border-2 border-accent rounded-lg px-4 py-2.5 font-bold text-accent focus:outline-none focus:ring-2 focus:ring-primary">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-accent text-[11px] font-bold uppercase tracking-widest mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="block w-full border-2 border-accent rounded-lg px-4 py-2.5 font-bold text-accent focus:outline-none focus:ring-2 focus:ring-primary">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-accent text-[11px] font-bold uppercase tracking-widest mb-2">Role</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="cashier" class="sr-only peer" {{ old('role', 'cashier') === 'cashier' ? 'checked' : '' }}>
                                <div class="flex flex-col items-center gap-1 p-3 rounded-xl border-2 border-gray-200 text-center transition-all peer-checked:border-accent peer-checked:bg-primary peer-checked:shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] hover:border-accent/50">
                                    <span class="text-2xl">🧾</span>
                                    <span class="text-xs font-black text-accent uppercase">Cashier</span>
                                    <span class="text-[10px] text-gray-500">POS & Products</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="admin" class="sr-only peer" {{ old('role') === 'admin' ? 'checked' : '' }}>
                                <div class="flex flex-col items-center gap-1 p-3 rounded-xl border-2 border-gray-200 text-center transition-all peer-checked:border-accent peer-checked:bg-primary peer-checked:shadow-[3px_3px_0px_0px_rgba(0,0,0,1)] hover:border-accent/50">
                                    <span class="text-2xl">👑</span>
                                    <span class="text-xs font-black text-accent uppercase">Admin</span>
                                    <span class="text-[10px] text-gray-500">Full access</span>
                                </div>
                            </label>
                        </div>
                        @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-accent text-[11px] font-bold uppercase tracking-widest mb-1">Password</label>
                        <input type="password" name="password" required
                               class="block w-full border-2 border-accent rounded-lg px-4 py-2.5 font-bold text-accent focus:outline-none focus:ring-2 focus:ring-primary">
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-accent text-[11px] font-bold uppercase tracking-widest mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" required
                               class="block w-full border-2 border-accent rounded-lg px-4 py-2.5 font-bold text-accent focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>

                    <button type="submit"
                            class="w-full bg-primary border-2 border-accent text-accent font-black py-3 rounded-xl shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:-translate-y-1 hover:shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] transition-all">
                        Create Staff Account
                    </button>
                </form>
            </div>

            {{-- ── EXISTING STAFF LIST ── --}}
            <div class="bg-white border-2 border-accent shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] rounded-xl overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b-2 border-accent/10 bg-wood-light">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-accent/60">Current Staff</p>
                    <h3 class="text-lg font-black text-accent">Admins & Cashiers</h3>
                </div>
                <div class="flex-1 overflow-y-auto divide-y divide-accent/10">
                    @forelse($staff as $member)
                        <div class="flex items-center justify-between px-6 py-4 hover:bg-wood-light/50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center font-black text-sm border-2 border-accent {{ $member->role === 'admin' ? 'bg-accent text-white' : 'bg-blue-500 text-white' }}">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-accent text-sm">{{ $member->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $member->email }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-black px-2 py-0.5 rounded-full border {{ $member->role === 'admin' ? 'bg-accent/10 text-accent border-accent/30' : 'bg-blue-100 text-blue-700 border-blue-200' }} uppercase tracking-wide">
                                    {{ $member->role === 'admin' ? '👑' : '🧾' }} {{ $member->role }}
                                </span>
                                @if($member->id !== auth()->id())
                                    <form action="{{ route('admin.staff.destroy', $member) }}" method="POST" onsubmit="return confirm('Remove {{ $member->name }}?')">
                                        @csrf @method('DELETE')
                                        <button class="text-xs text-red-500 hover:text-red-700 font-bold bg-red-50 px-2 py-1 rounded border border-red-200 transition-colors">Remove</button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400 italic">You</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center text-gray-400 font-medium">No staff accounts yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<nav x-data="{ open: false }" class="bg-primary border-b-2 border-accent sticky top-50 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">

            {{-- Brand --}}
            <div class="flex items-center gap-5">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 shrink-0 group ">
                    <img src="{{ asset('images/logo.svg') }}"
                         alt="KKV"
                         class="w-20 h-10 object-contain group-hover:scale-110 transition-transform shrink-0">
                </a>

                {{-- Role-specific nav links --}}
                @php $role = auth()->user()->role; @endphp
                <div class="hidden sm:flex items-center gap-5">

                    @if($role === 'admin' || $role === 'cashier')
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"> Dashboard</x-nav-link>
                        <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')"> Products</x-nav-link>
                        <x-nav-link :href="route('pos.index')" :active="request()->routeIs('pos.*')"> Checkout</x-nav-link>
                    @endif

                    @if($role === 'admin')
                        <x-nav-link :href="route('reports')" :active="request()->routeIs('reports*')"> Reports</x-nav-link>
                        <x-nav-link :href="route('admin.staff.create')" :active="request()->routeIs('admin.staff.*')"> Staff</x-nav-link>
                    @endif

                    @if($role === 'customer')
                        {{-- Customers: 2 tabs only --}}
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"> Dashboard</x-nav-link>
                        <x-nav-link :href="route('catalog')" :active="request()->routeIs('catalog')"> Products</x-nav-link>
                    @endif

                </div>
            </div>

            {{-- Right side: role badge + user dropdown --}}
            <div class="hidden sm:flex sm:items-center gap-3">
                @if($role === 'admin')
                    <span class="text-[10px] font-black uppercase tracking-widest bg-accent text-white px-2.5 py-1 rounded-full border-2 border-accent"> Admin</span>
                @elseif($role === 'cashier')
                    <span class="text-[10px] font-black uppercase tracking-widest bg-blue-500 text-white px-2.5 py-1 rounded-full border-2 border-blue-700"> Cashier</span>
                @elseif($role === 'customer')
                    <span class="text-[10px] font-black uppercase tracking-widest bg-green-500 text-white px-2.5 py-1 rounded-full border-2 border-green-700"> Customer</span>
                @endif

                <x-dropdown align="right" width=fit>
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-1.5 px-3 py-1.5 border-2 border-accent text-sm font-bold rounded-lg text-accent bg-white hover:bg-accent hover:text-white transition-all duration-150">
                            {{ Auth::user()->name }}
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Mobile hamburger --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open" class="p-2 rounded-md text-accent hover:bg-accent/10">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden border-t-2 border-accent/20">
        <div class="pt-2 pb-3 space-y-1 px-4">
            @php $role = auth()->user()->role; @endphp
            @if($role !== 'customer')
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"> Dashboard</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')"> Products</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('pos.index')" :active="request()->routeIs('pos.*')"> Checkout</x-responsive-nav-link>
                @if($role === 'admin')
                    <x-responsive-nav-link :href="route('reports')" :active="request()->routeIs('reports*')"> Reports</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.staff.create')" :active="request()->routeIs('admin.staff.*')"> Staff</x-responsive-nav-link>
                @endif
            @else
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"> Dashboard</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('catalog')" :active="request()->routeIs('catalog')">  Products</x-responsive-nav-link>
            @endif
        </div>
        <div class="pt-3 pb-3 border-t border-accent/20 px-4">
            <div class="font-bold text-accent text-sm">{{ Auth::user()->name }}</div>
            <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
            <div class="mt-2 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">Profile</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

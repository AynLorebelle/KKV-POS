<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-primary border-2 border-accent rounded-lg font-bold text-accent tracking-widest shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:translate-y-[2px] hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] active:translate-y-[4px] active:shadow-none focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition-all']) }}>
    {{ $slot }}
</button>

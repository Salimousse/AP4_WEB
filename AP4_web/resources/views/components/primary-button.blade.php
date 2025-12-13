<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-festival-primary border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-festival-secondary focus:bg-festival-secondary active:bg-festival-secondary focus:outline-none focus:ring-2 focus:ring-festival-primary focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>

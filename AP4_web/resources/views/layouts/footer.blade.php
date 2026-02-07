<footer class="bg-festival-dark border-t border-festival-dark py-8 sm:py-12 mt-12 sm:mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-8">
            <div class="text-center sm:text-left">
                <h4 class="text-lg sm:text-xl font-bold text-festival-light mb-2 sm:mb-4">Cale Sons</h4>
                <p class="text-sm sm:text-base text-festival-light/70">Festival de musique et de culture</p>
            </div>

            <div class="text-center sm:text-left">
                <h4 class="font-semibold text-festival-light mb-3 sm:mb-4 text-sm sm:text-base">À propos</h4>
                <ul class="space-y-2 text-xs sm:text-sm text-festival-light/70">
                    <li><a href="{{ route('about') }}" class="hover:text-festival-primary transition">À propos</a></li>
                    <li><a href="{{ route('festivals') }}" class="hover:text-festival-primary transition">Programme des festivals</a></li>
                </ul>
            </div>

            <div class="text-center sm:text-left">
                <h4 class="font-semibold text-festival-light mb-3 sm:mb-4 text-sm sm:text-base">Support</h4>
                <ul class="space-y-2 text-xs sm:text-sm text-festival-light/70">
                    <li><a href="{{ route('contact') }}" class="hover:text-festival-primary transition">Contact</a></li>
                    <li><a href="{{ route('support') }}" class="hover:text-festival-primary transition">Support</a></li>
                    <li><a href="{{ route('privacy') }}" class="hover:text-festival-primary transition">Confidentialité</a></li>
                    <li><a href="{{ route('terms') }}" class="hover:text-festival-primary transition">CGV</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-festival-light/20 mt-8 sm:mt-12 pt-6 sm:pt-8 text-center text-xs sm:text-sm text-festival-light/70">
            <p>&copy; 2026 Festival Cale Sons. Tous droits réservés.</p>
        </div>
    </div>
</footer>

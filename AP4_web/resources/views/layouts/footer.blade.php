<footer class="bg-festival-dark border-t border-festival-dark py-12 mt-16">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid md:grid-cols-3 gap-8">
            <div>
                <h4 class="text-xl font-bold text-festival-light mb-4">Cale Sons</h4>
                <p class="text-festival-light/70 text-sm">Festival de musique et de culture</p>
            </div>

            <div>
                <h4 class="font-semibold text-festival-light mb-4">À propos</h4>
                <ul class="space-y-2 text-sm text-festival-light/70">
                    <li><a href="{{ route('about') }}" class="hover:text-festival-primary transition">À propos</a></li>
                    <li><a href="{{ route('festivals') }}" class="hover:text-festival-primary transition">Programme des festivals</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold text-festival-light mb-4">Support</h4>
                <ul class="space-y-2 text-sm text-festival-light/70">
                    <li><a href="{{ route('contact') }}" class="hover:text-festival-primary transition">Contact</a></li>
                    <li><a href="{{ route('support') }}" class="hover:text-festival-primary transition">Support</a></li>
                    <li><a href="{{ route('privacy') }}" class="hover:text-festival-primary transition">Confidentialité</a></li>
                    <li><a href="{{ route('terms') }}" class="hover:text-festival-primary transition">CGV</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-festival-light/20 mt-12 pt-8 text-center text-sm text-festival-light/70">
            <p>&copy; 2026 Festival Cale Sons. Tous droits réservés.</p>
        </div>
    </div>
</footer>

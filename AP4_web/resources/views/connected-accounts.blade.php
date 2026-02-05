<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Comptes associés') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Messages Flash -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Succès !</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Erreur !</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Gérer vos connexions externes</h3>

                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                            <div>
                                <span class="text-gray-900 font-medium">Google</span>
                                @if(Auth::user()->google_email)
                                    <div class="text-xs text-gray-600 mt-0.5">{{ Auth::user()->google_email }}</div>
                                @endif
                            </div>
                        </div>

                        @if(Auth::user()->google_id)
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ✅ Connecté
                                </span>
                                <form method="POST" action="{{ route('auth.google.unlink') }}" onsubmit="return confirm('Voulez-vous vraiment délier ce compte Google ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-900 hover:underline focus:outline-none">
                                        Délier
                                    </button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('google-auth') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Lier mon compte
                            </a>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-3" viewBox="0 0 23 23" xmlns="http://www.w3.org/2000/svg">
                                <path fill="#f35325" d="M1 1h10v10H1z"/>
                                <path fill="#81bc06" d="M12 1h10v10H12z"/>
                                <path fill="#05a6f0" d="M1 12h10v10H1z"/>
                                <path fill="#ffba08" d="M12 12h10v10H12z"/>
                            </svg>
                            <div>
                                <span class="text-gray-900 font-medium">Microsoft</span>
                                @if(Auth::user()->microsoft_email)
                                    <div class="text-xs text-gray-600 mt-0.5">{{ Auth::user()->microsoft_email }}</div>
                                @endif
                            </div>
                        </div>

                        @if(Auth::user()->microsoft_id)
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ✅ Connecté
                                </span>
                                <form method="POST" action="{{ route('auth.microsoft.unlink') }}" onsubmit="return confirm('Voulez-vous vraiment délier ce compte Microsoft ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-900 hover:underline focus:outline-none">
                                        Délier
                                    </button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('auth.microsoft') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Lier mon compte
                            </a>
                        @endif
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path fill="#1877F2" d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            <div>
                                <span class="text-festival-dark font-medium">Facebook</span>
                                @if(Auth::user()->facebook_email)
                                    <div class="text-xs text-festival-dark/70 mt-0.5">{{ Auth::user()->facebook_email }}</div>
                                @endif
                            </div>
                        </div>

                        @if(Auth::user()->facebook_id)
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ✅ Connecté
                                </span>
                                <form method="POST" action="{{ route('auth.facebook.unlink') }}" onsubmit="return confirm('Voulez-vous vraiment délier ce compte Facebook ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-900 hover:underline focus:outline-none">
                                        Délier
                                    </button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('auth.facebook') }}" class="inline-flex items-center px-4 py-2 bg-white border border-festival-dark/20 rounded-md font-semibold text-xs text-festival-dark uppercase tracking-widest shadow-sm hover:bg-festival-light focus:outline-none focus:ring-2 focus:ring-festival-primary focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Lier mon compte
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

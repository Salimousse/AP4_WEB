<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    // Liste de tous les festivals
    public function festivals()
    {
        $festivals = \App\Models\Festival::all();
        return view('pages.festivals', compact('festivals'));
    }

    // Programme d'un festival spécifique
    public function festival($id)
    {
        $festival = \App\Models\Festival::with([
            'manifestations.concert',
            'manifestations.conference',
            'manifestations.atelier'
        ])->findOrFail($id);
        
        // Séparer les manifestations par type
        $concerts = $festival->manifestations->filter(fn($m) => $m->concert !== null);
        $conferences = $festival->manifestations->filter(fn($m) => $m->conference !== null);
        $ateliers = $festival->manifestations->filter(fn($m) => $m->atelier !== null);
        
        return view('pages.page-festival', compact('festival', 'concerts', 'conferences', 'ateliers'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function contact()
    {
        return view('pages.contact');
    }
}

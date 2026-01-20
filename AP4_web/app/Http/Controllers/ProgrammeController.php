<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Festival; // On appelle votre nouveau modèle

class ProgrammeController extends Controller
{
    public function index()
    {
        // On récupère le festival N°1 (celui créé dans le SQL) et ses manifs
        $festival = Festival::with('manifestations')->find(1);

        // Petite sécurité si la base est vide
        if (!$festival) {
            return "Erreur : Aucun festival trouvé avec l'ID 1. Avez-vous lancé le script SQL ?";
        }

        // On envoie les données à la vue
        return view('programme', compact('festival'));
    }
}
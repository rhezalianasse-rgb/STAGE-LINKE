<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\Entreprise;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntrepriseController extends Controller
{
    public function dashboard()
    {
        $entreprise = Auth::user()->entreprise;
        $stats = [
            'offres_publiees' => $entreprise->offres()->where('statut', 'active')->count(),
            'candidatures_recues' => $this->companyCandidatures($entreprise)->count(),
            'candidatures_attente' => $this->companyCandidatures($entreprise)->where('statut', 'en_attente')->count(),
            'candidatures_acceptees' => $this->companyCandidatures($entreprise)->where('statut', 'acceptee')->count(),
        ];
        $recentCandidatures = $this->companyCandidatures($entreprise)
            ->with(['stagiaire.user', 'offre'])
            ->latest()
            ->take(5)
            ->get();

        return view('entreprise.dashboard', compact('entreprise', 'stats', 'recentCandidatures'));
    }

    public function profil()
    {
        $entreprise = Auth::user()->entreprise;

        return view('entreprise.profil', compact('entreprise'));
    }

    public function updateProfil(Request $request)
    {
        $user = Auth::user();
        $entreprise = $user->entreprise;

        $request->validate([
            'nom' => 'required|string|max:100',
            'description' => 'nullable|string',
            'secteur_activite' => 'nullable|string|max:100',
            'adresse' => 'nullable|string',
            'site_web' => 'nullable|url|max:100',
            'telephone' => 'nullable|string|max:20',
            'logo' => 'nullable|image|max:2048',
        ]);

        $user->update([
            'telephone' => $request->telephone,
        ]);

        $entrepriseData = [
            'nom_entreprise' => $request->nom,
            'description' => $request->description,
            'secteur_activite' => $request->secteur_activite,
            'adresse' => $request->adresse,
            'site_web' => $request->site_web,
            'telephone' => $request->telephone,
        ];

        if ($request->hasFile('logo')) {
            $entrepriseData['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        $entreprise->update($entrepriseData);

        return back()->with('success', 'Profil entreprise mis à jour.');
    }

    public function mesCandidatures(Request $request)
    {
        $entreprise = Auth::user()->entreprise;
        $validated = $request->validate([
            'statut' => 'nullable|in:en_attente,acceptee,refusee',
        ]);
        $candidatures = $this->companyCandidatures($entreprise)
            ->with(['stagiaire.user', 'offre'])
            ->when($validated['statut'] ?? null, fn ($query, $statut) => $query->where('statut', $statut))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('entreprise.candidatures', compact('candidatures'));
    }

    public function voirCandidature($id)
    {
        $entreprise = Auth::user()->entreprise;
        $candidature = $this->companyCandidatures($entreprise)
            ->with(['stagiaire.user', 'stagiaire.competences', 'offre'])
            ->findOrFail($id);

        return view('entreprise.candidature_detail', compact('candidature'));
    }

    public function downloadCv($id)
    {
        $entreprise = Auth::user()->entreprise;
        $candidature = $this->companyCandidatures($entreprise)
            ->with('stagiaire')
            ->findOrFail($id);

        return $this->streamPrivateCv($candidature->cv_path ?? $candidature->stagiaire->cv_path);
    }

    public function updateCandidature(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,acceptee,refusee',
        ]);

        $entreprise = Auth::user()->entreprise;
        $candidature = $this->companyCandidatures($entreprise)
            ->with(['stagiaire', 'offre'])
            ->findOrFail($id);

        $candidature->update(['statut' => $request->statut]);

        Notification::create([
            'utilisateur_id' => $candidature->stagiaire->user_id,
            'message' => 'Le statut de votre candidature pour '.$candidature->offre->titre.' est maintenant : '.$request->statut,
            'type' => 'candidature',
        ]);

        return back()->with('success', 'Statut de la candidature mis à jour.');
    }

    private function companyCandidatures(Entreprise $entreprise): Builder
    {
        return Candidature::query()->whereHas(
            'offre',
            fn ($query) => $query->where('entreprise_id', $entreprise->getKey())
        );
    }
}

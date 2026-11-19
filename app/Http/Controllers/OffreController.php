<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\Competence;
use App\Models\Notification;
use App\Models\Offre;
use App\Models\Specialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OffreController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'keyword' => 'nullable|string|max:255',
            'type_stage' => 'nullable|in:PFE,Stage d\'été,Stage d\'observation,Stage pré-emploi',
            'localisation' => 'nullable|string|max:100',
            'duree' => 'nullable|in:1-2,3-4,5-6,+6',
            'specialite_id' => 'nullable|integer|exists:specialites,id',
        ]);

        $query = Offre::with(['entreprise', 'competences'])
            ->publiclyAvailable()
            ->withCount('competences');

        if (! empty($validated['keyword'])) {
            $query->where('titre', 'like', '%'.$validated['keyword'].'%');
        }
        if (! empty($validated['type_stage'])) {
            $query->where('type_stage', $validated['type_stage']);
        }
        if (! empty($validated['localisation'])) {
            $query->where('localisation', 'like', '%'.$validated['localisation'].'%');
        }
        if (! empty($validated['specialite_id'])) {
            $query->whereHas('entreprise.specialites', function ($query) use ($validated) {
                $query->where('specialites.id', $validated['specialite_id']);
            });
        }
        if (! empty($validated['duree'])) {
            [$minimumDuration, $maximumDuration] = match ($validated['duree']) {
                '1-2' => [1, 2],
                '3-4' => [3, 4],
                '5-6' => [5, 6],
                '+6' => [7, null],
            };

            if ($maximumDuration === null) {
                $query->where('duree', '>=', $minimumDuration);
            } else {
                $query->whereBetween('duree', [$minimumDuration, $maximumDuration]);
            }
        }

        $offres = $query->orderBy('date_publication', 'desc')->paginate(10);
        $favorisIds = [];
        $stagiaireCompetencesIds = [];

        if (Auth::check() && Auth::user()->role === 'stagiaire') {
            $stagiaire = Auth::user()->stagiaire;
            $favorisIds = $stagiaire->favoris()->pluck('offre_id')->all();
            $stagiaireCompetencesIds = $stagiaire->competences()->pluck('competences.id')->all();
        }

        $specialites = Specialite::query()->orderBy('nom')->get();

        return view('offres.index', compact('offres', 'favorisIds', 'specialites', 'stagiaireCompetencesIds'));
    }

    public function show($id)
    {
        $offre = Offre::publiclyAvailable()->with(['entreprise', 'competences'])->findOrFail($id);
        $matchingRate = 0;
        $isFavori = false;
        $hasApplied = false;

        if (Auth::check() && Auth::user()->role === 'stagiaire') {
            $stagiaire = Auth::user()->stagiaire;
            $isFavori = $stagiaire->favoris()->where('offre_id', $offre->id)->exists();
            $hasApplied = $stagiaire->candidatures()->where('offre_id', $offre->id)->exists();
            $offreCompetences = $offre->competences->pluck('id')->toArray();
            if (count($offreCompetences) > 0) {
                $stagiaireCompetences = $stagiaire->competences->pluck('id')->toArray();
                $common = array_intersect($offreCompetences, $stagiaireCompetences);
                $matchingRate = (count($common) / count($offreCompetences)) * 100;
            }
        }

        return view('offres.show', compact('offre', 'matchingRate', 'isFavori', 'hasApplied'));
    }

    public function create()
    {
        $competences = Competence::all();

        return view('offres.create', compact('competences'));
    }

    public function mesOffres()
    {
        $offres = Auth::user()->entreprise->offres()->latest()->paginate(10);

        return view('entreprise.offres', compact('offres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:200',
            'description' => 'required|string',
            'type_stage' => 'required|in:PFE,Stage d\'été,Stage d\'observation,Stage pré-emploi',
            'duree' => 'required|integer|min:1|max:12',
            'localisation' => 'required|string|max:100',
            'date_expiration' => 'nullable|date|after_or_equal:today',
            'competences' => 'nullable|array',
            'competences.*' => 'exists:competences,id',
        ]);

        $entreprise = Auth::user()->entreprise;

        $offre = Offre::create([
            'entreprise_id' => $entreprise->id,
            'titre' => $request->titre,
            'description' => $request->description,
            'type_stage' => $request->type_stage,
            'duree' => $request->duree,
            'localisation' => $request->localisation,
            'date_publication' => now()->toDateString(),
            'date_expiration' => $request->date_expiration,
            'statut' => 'active',
        ]);

        if ($request->has('competences')) {
            $offre->competences()->attach($request->competences);
        }

        return redirect()->route('entreprise.dashboard')->with('success', 'Offre de stage publiée avec succès.');
    }

    public function edit($id)
    {
        $offre = Offre::findOrFail($id);

        if ($offre->entreprise_id !== Auth::user()->entreprise->id) {
            abort(403);
        }

        $competences = Competence::all();
        $selectedCompetences = $offre->competences->pluck('id')->toArray();

        return view('offres.edit', compact('offre', 'competences', 'selectedCompetences'));
    }

    public function update(Request $request, $id)
    {
        $offre = Offre::findOrFail($id);

        if ($offre->entreprise_id !== Auth::user()->entreprise->id) {
            abort(403);
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:200',
            'description' => 'required|string',
            'type_stage' => 'required|in:PFE,Stage d\'été,Stage d\'observation,Stage pré-emploi',
            'duree' => 'required|integer|min:1|max:12',
            'localisation' => 'required|string|max:100',
            'date_expiration' => 'nullable|date|after_or_equal:today',
            'statut' => 'required|in:active,inactive',
            'competences' => 'nullable|array',
            'competences.*' => 'exists:competences,id',
        ]);

        $offre->update([
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'type_stage' => $validated['type_stage'],
            'duree' => $validated['duree'],
            'localisation' => $validated['localisation'],
            'date_expiration' => $validated['date_expiration'] ?? null,
            'statut' => $validated['statut'],
        ]);

        if (! empty($validated['competences'])) {
            $offre->competences()->sync($validated['competences']);
        } else {
            $offre->competences()->sync([]);
        }

        return redirect()->route('entreprise.dashboard')->with('success', 'Offre mise à jour.');
    }

    public function destroy($id)
    {
        $offre = Offre::findOrFail($id);

        if ($offre->entreprise_id !== Auth::user()->entreprise->id) {
            abort(403);
        }

        $offre->delete();

        return back()->with('success', 'Offre supprimée.');
    }

    public function postuler(Request $request, $id)
    {
        $request->validate([
            'lettre_motivation' => 'nullable|string',
            'cv' => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        $offre = Offre::publiclyAvailable()->findOrFail($id);
        $stagiaire = Auth::user()->stagiaire;

        // A stagiaire may apply only once to the same offer.
        if ($stagiaire->candidatures()->where('offre_id', $offre->id)->exists()) {
            return back()->with('error', 'Vous avez déjà postulé à cette offre.');
        }

        $candidatureData = [
            'offre_id' => $offre->id,
            'stagiaire_id' => $stagiaire->id,
            'date_candidature' => now(),
            'statut' => 'en_attente',
            'lettre_motivation' => $request->lettre_motivation,
        ];

        if ($request->hasFile('cv')) {
            $candidatureData['cv_path'] = $request->file('cv')->store('cvs', 'private');
        } elseif ($stagiaire->cv_path) {
            $candidatureData['cv_path'] = $stagiaire->cv_path;
        }

        Candidature::create($candidatureData);

        Notification::create([
            'utilisateur_id' => $offre->entreprise->user->id,
            'message' => 'Vous avez reçu une nouvelle candidature pour l\'offre : '.$offre->titre,
            'type' => 'candidature',
        ]);

        return back()->with('success', 'Votre candidature a été envoyée.');
    }

    public function toggleFavori($id)
    {
        $offre = Offre::publiclyAvailable()->findOrFail($id);
        $stagiaire = Auth::user()->stagiaire;

        $favori = $stagiaire->favoris()->where('offre_id', $offre->id)->first();

        if ($favori) {
            $favori->delete();

            return back()->with('success', 'Offre retirée de vos favoris.');
        } else {
            $stagiaire->favoris()->create([
                'offre_id' => $offre->id,
                'date_ajout' => now(),
            ]);

            return back()->with('success', 'Offre ajoutée à vos favoris.');
        }
    }
}

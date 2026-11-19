<?php

namespace App\Http\Controllers;

use App\Models\Competence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StagiaireController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $stagiaire = $user->stagiaire;
        $stats = [
            'total_candidatures' => $stagiaire->candidatures()->count(),
            'candidatures_acceptees' => $stagiaire->candidatures()->where('statut', 'acceptee')->count(),
            'favoris' => $stagiaire->favoris()->count(),
            'notifications_non_lues' => $user->unreadNotifications()->count(),
        ];
        $recentCandidatures = $stagiaire->candidatures()
            ->with('offre.entreprise')
            ->latest()
            ->take(5)
            ->get();
        $notifications = $user->notifications()->orderBy('date_envoi', 'desc')->take(5)->get();

        return view('stagiaire.dashboard', compact('stagiaire', 'stats', 'recentCandidatures', 'notifications'));
    }

    public function profil()
    {
        $stagiaire = Auth::user()->stagiaire;

        return view('stagiaire.profil', compact('stagiaire'));
    }

    public function profilePhoto(string $filename)
    {
        abort_unless(preg_match('/\A[a-zA-Z0-9][a-zA-Z0-9._-]*\.[a-zA-Z0-9]+\z/', $filename), 404);

        $disk = Storage::disk('public');
        $path = 'photos/' . $filename;
        abort_unless($disk->exists($path), 404);

        return $disk->response($path);
    }

    public function downloadCv()
    {
        return $this->streamPrivateCv(Auth::user()->stagiaire->cv_path);
    }

    public function updateProfil(Request $request)
    {
        $user = Auth::user();
        $stagiaire = $user->stagiaire;

        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'date_naissance' => 'nullable|date',
            'adresse' => 'nullable|string',
            'niveau_etude' => 'nullable|string|max:100',
            'domaine' => 'nullable|string|max:100',
            'lettre_motivation' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'cv' => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        $user->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'telephone' => $request->telephone,
        ]);

        $stagiaireData = [
            'date_naissance' => $request->date_naissance,
            'adresse' => $request->adresse,
            'niveau_etude' => $request->niveau_etude,
            'domaine' => $request->domaine,
            'lettre_motivation' => $request->lettre_motivation,
        ];

        if ($request->hasFile('photo')) {
            $stagiaireData['photo_path'] = $request->file('photo')->store('photos', 'public');
        }

        if ($request->hasFile('cv')) {
            $stagiaireData['cv_path'] = $request->file('cv')->store('cvs', 'private');
        }

        $stagiaire->update($stagiaireData);

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    public function competences()
    {
        $stagiaire = Auth::user()->stagiaire;
        $mesCompetences = $stagiaire->competences()->orderBy('nom')->get();
        $disponibles = Competence::query()
            ->whereNotIn('id', $mesCompetences->modelKeys())
            ->orderBy('nom')
            ->get();

        return view('stagiaire.competences', compact('mesCompetences', 'disponibles'));
    }

    public function addCompetence(Request $request)
    {
        $request->validate([
            'competence_id' => 'required|exists:competences,id',
        ]);

        $stagiaire = Auth::user()->stagiaire;
        $stagiaire->competences()->syncWithoutDetaching([$request->competence_id]);

        return back()->with('success', 'Compétence ajoutée.');
    }

    public function removeCompetence($id)
    {
        $stagiaire = Auth::user()->stagiaire;
        $stagiaire->competences()->detach($id);

        return back()->with('success', 'Compétence retirée.');
    }

    public function mesCandidatures()
    {
        $stagiaire = Auth::user()->stagiaire;
        $candidatures = $stagiaire->candidatures()
            ->with('offre.entreprise')
            ->latest()
            ->paginate(10);

        return view('stagiaire.candidatures', compact('candidatures'));
    }

    public function mesFavoris()
    {
        $stagiaire = Auth::user()->stagiaire;
        $favoris = $stagiaire->favoris()
            ->with('offre.entreprise')
            ->latest()
            ->paginate(10);

        return view('stagiaire.favoris', compact('favoris'));
    }
}

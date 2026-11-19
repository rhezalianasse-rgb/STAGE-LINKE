<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\Competence;
use App\Models\Entreprise;
use App\Models\Offre;
use App\Models\Specialite;
use App\Models\Stagiaire;
use App\Models\User;
use App\Services\EmailVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'users' => User::count(),
            'stagiaires' => Stagiaire::count(),
            'entreprises' => Entreprise::count(),
            'offres' => Offre::publiclyAvailable()->count(),
            'candidatures_total' => Candidature::count(),
            'candidatures_attente' => Candidature::where('statut', 'en_attente')->count(),
            'candidatures_acceptees' => Candidature::where('statut', 'acceptee')->count(),
            'candidatures_refusees' => Candidature::where('statut', 'refusee')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function stagiaires(Request $request)
    {
        $validated = $request->validate(['search' => 'nullable|string|max:100']);
        $search = $validated['search'] ?? null;

        $stagiaires = Stagiaire::with('user')
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($query) use ($search) {
                    $query->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.stagiaires', compact('stagiaires'));
    }

    public function showStagiaire($id)
    {
        $stagiaire = Stagiaire::with(['user', 'competences', 'candidatures.offre.entreprise'])->findOrFail($id);

        return view('admin.stagiaire_detail', compact('stagiaire'));
    }

    public function downloadStagiaireCv($id)
    {
        $stagiaire = Stagiaire::findOrFail($id);

        return $this->streamPrivateCv($stagiaire->cv_path);
    }

    public function deleteStagiaire($id)
    {
        $stagiaire = Stagiaire::findOrFail($id);
        $stagiaire->user->delete();

        // user deletion should cascade or stagiaire deleted too
        return back()->with('success', 'Stagiaire supprimé.');
    }

    public function entreprises(Request $request)
    {
        $validated = $request->validate(['search' => 'nullable|string|max:100']);
        $search = $validated['search'] ?? null;

        $entreprises = Entreprise::with('user')
            ->withCount('offres')
            ->when($search, function ($query, $search) {
                $query->where('nom_entreprise', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($query) => $query->where('email', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.entreprises', compact('entreprises'));
    }

    public function createEntreprise()
    {
        return view('admin.entreprise_create');
    }

    public function storeEntreprise(Request $request, EmailVerificationService $verification)
    {
        $validated = $request->validate([
            'nom_entreprise' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'telephone' => 'nullable|string|max:20',
            'secteur_activite' => 'nullable|string|max:100',
            'adresse' => 'nullable|string',
            'site_web' => 'nullable|url|max:100',
            'mot_de_passe' => ['required', 'string', PasswordRule::min(12)->mixedCase()->letters()->numbers()->symbols(), 'confirmed'],
        ]);

        $user = DB::transaction(function () use ($validated): User {
            $user = User::create([
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'email' => $validated['email'],
                'telephone' => $validated['telephone'] ?? null,
                'mot_de_passe' => Hash::make($validated['mot_de_passe']),
                'role' => 'entreprise',
                'statut' => 'actif',
                'email_verified_at' => null,
            ]);

            Entreprise::create([
                'user_id' => $user->id,
                'nom_entreprise' => $validated['nom_entreprise'],
                'telephone' => $validated['telephone'] ?? null,
                'secteur_activite' => $validated['secteur_activite'] ?? null,
                'adresse' => $validated['adresse'] ?? null,
                'site_web' => $validated['site_web'] ?? null,
            ]);

            return $user;
        });

        $sendResult = $verification->sendCode($user);

        return redirect()->route('admin.entreprises')->with(
            $sendResult->succeeded() ? 'success' : 'error',
            $sendResult->succeeded()
                ? 'Entreprise créée. Un code de vérification a été envoyé à son adresse e-mail.'
                : 'Entreprise créée, mais le code de vérification n’a pas été envoyé : '.$sendResult->message()
        );
    }

    public function showEntreprise($id)
    {
        $entreprise = Entreprise::with(['user', 'offres'])->findOrFail($id);

        return view('admin.entreprise_detail', compact('entreprise'));
    }

    public function deleteEntreprise($id)
    {
        $entreprise = Entreprise::findOrFail($id);
        $entreprise->user->delete();

        return back()->with('success', 'Entreprise supprimée.');
    }

    public function offres(Request $request)
    {
        $validated = $request->validate(['search' => 'nullable|string|max:100']);
        $search = $validated['search'] ?? null;

        $offres = Offre::with('entreprise')
            ->withCount('candidatures')
            ->when($search, function ($query, $search) {
                $query->where('titre', 'like', "%{$search}%")
                    ->orWhereHas('entreprise', fn ($query) => $query->where('nom_entreprise', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.offres', compact('offres'));
    }

    public function deleteOffre($id)
    {
        $offre = Offre::findOrFail($id);
        $offre->delete();

        return back()->with('success', 'Offre supprimée.');
    }

    public function candidatures(Request $request)
    {
        $validated = $request->validate(['search' => 'nullable|string|max:100']);
        $search = $validated['search'] ?? null;

        $candidatures = Candidature::with(['stagiaire.user', 'offre.entreprise'])
            ->when($search, function ($query, $search) {
                $query->whereHas('stagiaire.user', function ($query) use ($search) {
                    $query->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('offre', fn ($query) => $query->where('titre', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.candidatures', compact('candidatures'));
    }

    public function deleteCandidature($id)
    {
        Candidature::findOrFail($id)->delete();

        return back()->with('success', 'Candidature supprimée.');
    }

    public function specialites()
    {
        $specialites = Specialite::all();

        return view('admin.specialites', compact('specialites'));
    }

    public function storeSpecialite(Request $request)
    {
        $request->validate(['nom' => 'required|string|max:100']);
        Specialite::create($request->only('nom', 'description'));

        return back()->with('success', 'Spécialité ajoutée.');
    }

    public function updateSpecialite(Request $request, $id)
    {
        $request->validate(['nom' => 'required|string|max:100']);
        $specialite = Specialite::findOrFail($id);
        $specialite->update($request->only('nom', 'description'));

        return back()->with('success', 'Spécialité mise à jour.');
    }

    public function deleteSpecialite($id)
    {
        Specialite::findOrFail($id)->delete();

        return back()->with('success', 'Spécialité supprimée.');
    }

    public function competences()
    {
        $competences = Competence::all();

        return view('admin.competences', compact('competences'));
    }

    public function storeCompetence(Request $request)
    {
        $request->validate(['nom' => 'required|string|max:100']);
        Competence::create($request->only('nom', 'description'));

        return back()->with('success', 'Compétence ajoutée.');
    }

    public function updateCompetence(Request $request, $id)
    {
        $request->validate(['nom' => 'required|string|max:100']);
        $competence = Competence::findOrFail($id);
        $competence->update($request->only('nom', 'description'));

        return back()->with('success', 'Compétence mise à jour.');
    }

    public function deleteCompetence($id)
    {
        Competence::findOrFail($id)->delete();

        return back()->with('success', 'Compétence supprimée.');
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        $user->update(['statut' => $user->statut === 'actif' ? 'inactif' : 'actif']);

        return back()->with('success', 'Statut de l\'utilisateur modifié.');
    }
}

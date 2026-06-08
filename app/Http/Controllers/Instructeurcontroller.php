<?php
 
namespace App\Http\Controllers;
 
use App\Models\Instructeur;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
 
class InstructeurController extends Controller
{
    // Show all instructors in an overview table
    public function index(): View
    {
        $instructeurs = Instructeur::GetAllInstructeurs();
 
        return view('instructeur.index', [
            'instructeurs' => $instructeurs,
        ]);
    }
 
    // Show the blank create-instructor form
    public function create(): View
    {
        return view('instructeur.create');
    }
 
    // Validate input, create contact + instructor rows
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'voornaam'        => 'required',
            'achternaam'      => 'required',
            'email'           => 'required|email|unique:Contact,Email',
            'telefoon'        => 'required',
            'rijbewijsnummer' => 'required|unique:Instructor,LicenseNumber',
        ], [
            'voornaam.required'           => 'Voornaam is verplicht.',
            'achternaam.required'         => 'Achternaam is verplicht.',
            'email.required'              => 'E-mailadres is verplicht.',
            'email.email'                 => 'Vul een geldig e-mailadres in.',
            'email.unique'                => 'Dit e-mailadres is al in gebruik.',
            'telefoon.required'           => 'Telefoonnummer is verplicht.',
            'rijbewijsnummer.required'    => 'Rijbewijsnummer is verplicht.',
            'rijbewijsnummer.unique'      => 'Dit rijbewijsnummer is al geregistreerd.',
        ]);
 
        // Contact must exist before instructor row can be inserted
        $contactId = Instructeur::CreateContact($request->only([
            'voornaam', 'achternaam', 'email', 'telefoon',
        ]));
 
        Instructeur::CreateInstructeur($contactId, $request->only(['rijbewijsnummer']));
 
        return redirect()->route('instructeurs.index')
            ->with('success', 'Instructeur succesvol toegevoegd.');
    }
 
    // Show detail view for one instructor
    public function show(int $id): View
    {
        $instructeur = Instructeur::GetInstructeurById($id);
 
        abort_if(is_null($instructeur), 404, 'Geen instructeurs gevonden');
 
        return view('instructeur.show', ['instructeur' => $instructeur]);
    }
 
    // Show pre-filled edit form
    public function edit(int $id): View
    {
        $instructeur = Instructeur::GetInstructeurById($id);
 
        abort_if(is_null($instructeur), 404, 'Geen instructeurs gevonden');
 
        return view('instructeur.edit', ['instructeur' => $instructeur]);
    }
 
    // Validate and persist edits to contact + instructor rows
    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'voornaam'        => 'required',
            'achternaam'      => 'required',
            // Ignore current instructor's email when checking uniqueness
            'email'           => 'required|email|unique:Contact,Email,' . $id . ',ContactID',
            'telefoon'        => 'required',
            'rijbewijsnummer' => 'required|unique:Instructor,LicenseNumber,' . $id . ',InstructorID',
        ], [
            'voornaam.required'        => 'Voornaam is verplicht.',
            'achternaam.required'      => 'Achternaam is verplicht.',
            'email.required'           => 'E-mailadres is verplicht.',
            'email.email'              => 'Vul een geldig e-mailadres in.',
            'email.unique'             => 'Dit e-mailadres is al in gebruik.',
            'telefoon.required'        => 'Telefoonnummer is verplicht.',
            'rijbewijsnummer.required' => 'Rijbewijsnummer is verplicht.',
            'rijbewijsnummer.unique'   => 'Dit rijbewijsnummer is al geregistreerd.',
        ]);
 
        Instructeur::UpdateInstructeur($id, $request->only([
            'voornaam', 'achternaam', 'email', 'telefoon', 'rijbewijsnummer', 'is_active',
        ]));
 
        return redirect()->route('instructeurs.index')
            ->with('success', 'Instructeur succesvol bijgewerkt.');
    }
 
    // Delete instructor and linked contact row
    public function destroy(int $id): RedirectResponse
    {
        $instructeur = Instructeur::GetInstructeurById($id);
 
        abort_if(is_null($instructeur), 404, 'Geen instructeurs gevonden');
 
        Instructeur::DeleteInstructeur($id);
 
        return redirect()->route('instructeurs.index')
            ->with('success', 'Instructeur succesvol verwijderd.');
    }
}
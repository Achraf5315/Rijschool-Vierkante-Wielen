<?php
 
namespace App\Http\Controllers;
 
use App\Models\Instructeur;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
 use Illuminate\Support\Facades\Log;
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
 
    // Validate input, create contact and instructor records
public function store(Request $request): RedirectResponse
{
    try {

        $request->validate([
            'voornaam' => 'required',
            'achternaam' => 'required',
            'email' => 'required|email|unique:Contact,Email',
            'telefoon' => 'required',
            'rijbewijsnummer' => 'required|unique:Instructor,LicenseNumber',
        ]);

        $contactId = Instructeur::CreateContact(
            $request->only([
                'voornaam',
                'achternaam',
                'email',
                'telefoon'
            ])
        );

        Instructeur::CreateInstructeur(
            $contactId,
            $request->only([
                'rijbewijsnummer'
            ])
        );

        return redirect()
            ->route('instructeurs.index')
            ->with('success', 'Instructeur succesvol toegevoegd.');

    } catch (\Exception $e) {

        Log::error('Instructeur toevoegen mislukt', [
            'error' => $e->getMessage()
        ]);

        return back()
            ->withInput()
            ->with('error', 'Error 400 - Ongeldige invoer.');
    }
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
 
   // Validate input and update an instructor
public function update(Request $request, int $id): RedirectResponse
{
    try {

        $request->validate([
            'voornaam' => 'required',
            'achternaam' => 'required',
            'email' => 'required|email|unique:Contact,Email,' . $id . ',Id',
            'telefoon' => 'required',
            'rijbewijsnummer' => 'required|unique:Instructor,LicenseNumber,' . $id . ',Id',
        ]);

        Instructeur::UpdateInstructeur(
            $id,
            $request->only([
                'voornaam',
                'achternaam',
                'email',
                'telefoon',
                'rijbewijsnummer',
                'is_active'
            ])
        );

        return redirect()
            ->route('instructeurs.index')
            ->with('success', 'Instructeur succesvol bijgewerkt.');

    } catch (\Exception $e) {

        Log::error('Instructeur wijzigen mislukt', [
            'id' => $id,
            'error' => $e->getMessage()
        ]);

        return back()
            ->withInput()
            ->with('error', 'Error 400 - Ongeldige invoer.');
    }
}
 
   // Delete an instructor from the system
public function destroy(int $id): RedirectResponse
{
    try {

        $instructeur = Instructeur::GetInstructeurById($id);

        abort_if(is_null($instructeur), 404, 'Geen instructeurs gevonden');

        Instructeur::DeleteInstructeur($id);

        return redirect()
            ->route('instructeurs.index')
            ->with('success', 'Instructeur succesvol verwijderd.');

    } catch (\Exception $e) {

        Log::error('Instructeur verwijderen mislukt', [
            'id' => $id,
            'error' => $e->getMessage()
        ]);

        return redirect()
            ->route('instructeurs.index')
            ->with('error', 'Verwijderen mislukt.');
    }
}
}
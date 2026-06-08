<?php
 
namespace App\Http\Controllers;
 
use App\Models\Auto;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
 
class AutoController extends Controller
{
    // Show paginated list of all vehicles
    public function index(): View
    {
        $autos = Auto::GetAllAutos();
 
        return view('auto.index', [
            'autos' => $autos,
        ]);
    }
 
    // Show the blank create-vehicle form
    public function create(): View
    {
        return view('auto.create');
    }
 
    // Validate input and persist a new vehicle
    public function store(Request $request): RedirectResponse
    {
        // Validate all required fields against the Vehicle table
        $request->validate([
            'kenteken'  => 'required|unique:Vehicle,LicensePlate',
            'merk'      => 'required',
            'model'     => 'required',
            'bouwjaar'  => 'required|integer|min:1990|max:' . date('Y'),
        ], [
            // Dutch error messages
            'kenteken.required'  => 'Kenteken is verplicht.',
            'kenteken.unique'    => 'Dit kenteken is al geregistreerd.',
            'merk.required'      => 'Merk is verplicht.',
            'model.required'     => 'Model is verplicht.',
            'bouwjaar.required'  => 'Bouwjaar is verplicht.',
            'bouwjaar.integer'   => 'Bouwjaar moet een geldig jaar zijn.',
            'bouwjaar.min'       => 'Bouwjaar mag niet voor 1990 liggen.',
            'bouwjaar.max'       => 'Bouwjaar mag niet in de toekomst liggen.',
        ]);
 
        Auto::CreateAuto($request->only(['kenteken', 'merk', 'model', 'bouwjaar']));
 
        return redirect()->route('autos.index')
            ->with('success', 'Auto succesvol toegevoegd.');
    }
 
    // Show detail view of a single vehicle
    public function show(int $id): View
    {
        $auto = Auto::GetAutoById($id);
 
        abort_if(is_null($auto), 404, 'Geen voertuigen gevonden');
 
        return view('auto.show', ['auto' => $auto]);
    }
 
    // Show pre-filled edit form
    public function edit(int $id): View
    {
        $auto = Auto::GetAutoById($id);
 
        abort_if(is_null($auto), 404, 'Geen voertuigen gevonden');
 
        return view('auto.edit', ['auto' => $auto]);
    }
 
    // Validate and persist edits to an existing vehicle
    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'kenteken'  => 'required|unique:Vehicle,LicensePlate,' . $id . ',VehicleID',
            'merk'      => 'required',
            'model'     => 'required',
            'bouwjaar'  => 'required|integer|min:1990|max:' . date('Y'),
        ], [
            'kenteken.required' => 'Kenteken is verplicht.',
            'kenteken.unique'   => 'Dit kenteken is al in gebruik.',
            'merk.required'     => 'Merk is verplicht.',
            'model.required'    => 'Model is verplicht.',
            'bouwjaar.required' => 'Bouwjaar is verplicht.',
            'bouwjaar.integer'  => 'Bouwjaar moet een geldig jaar zijn.',
            'bouwjaar.min'      => 'Bouwjaar mag niet voor 1990 liggen.',
            'bouwjaar.max'      => 'Bouwjaar mag niet in de toekomst liggen.',
        ]);
 
        Auto::UpdateAuto($id, $request->only(['kenteken', 'merk', 'model', 'bouwjaar', 'is_available']));
 
        return redirect()->route('autos.index')
            ->with('success', 'Auto succesvol bijgewerkt.');
    }
 
    // Delete a vehicle record
    public function destroy(int $id): RedirectResponse
    {
        $auto = Auto::GetAutoById($id);
 
        abort_if(is_null($auto), 404, 'Geen voertuigen gevonden');
 
        Auto::DeleteAuto($id);
 
        return redirect()->route('autos.index')
            ->with('success', 'Auto succesvol verwijderd.');
    }
}
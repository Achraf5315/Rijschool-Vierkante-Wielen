<?php
 
namespace App\Http\Controllers;
 
use App\Models\Auto;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
 use Illuminate\Support\Facades\Log;
 
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
 
// Validate input and store a new vehicle
public function store(Request $request): RedirectResponse
{
    try {

        $request->validate([
            'kenteken' => 'required|unique:Vehicle,LicensePlate',
            'merk' => 'required',
            'model' => 'required',
            'bouwjaar' => 'required|integer|min:1990|max:' . date('Y'),
        ], [
            'kenteken.required' => 'Kenteken is verplicht.',
            'kenteken.unique' => 'Dit kenteken is al geregistreerd.',
            'merk.required' => 'Merk is verplicht.',
            'model.required' => 'Model is verplicht.',
            'bouwjaar.required' => 'Bouwjaar is verplicht.',
            'bouwjaar.integer' => 'Bouwjaar moet een geldig jaar zijn.',
            'bouwjaar.min' => 'Bouwjaar mag niet voor 1990 liggen.',
            'bouwjaar.max' => 'Bouwjaar mag niet in de toekomst liggen.',
        ]);

        Auto::CreateAuto(
            $request->only([
                'kenteken',
                'merk',
                'model',
                'bouwjaar'
            ])
        );

        return redirect()
            ->route('autos.index')
            ->with('success', 'Auto succesvol toegevoegd.');

    } catch (\Exception $e) {

        Log::error('Auto toevoegen mislukt', [
            'error' => $e->getMessage()
        ]);

        return back()
            ->withInput()
            ->with('error', 'Error 400 - Ongeldige invoer.');
    }
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
 
    // Validate input and update an existing vehicle
public function update(Request $request, int $id): RedirectResponse
{
    try {

        $request->validate([
            'kenteken' => 'required|unique:Vehicle,LicensePlate,' . $id . ',Id',
            'merk' => 'required',
            'model' => 'required',
            'bouwjaar' => 'required|integer|min:1990|max:' . date('Y'),
        ]);

        Auto::UpdateAuto(
            $id,
            $request->only([
                'kenteken',
                'merk',
                'model',
                'bouwjaar',
                'is_available'
            ])
        );

        return redirect()
            ->route('autos.index')
            ->with('success', 'Auto succesvol bijgewerkt.');

    } catch (\Exception $e) {

        Log::error('Auto wijzigen mislukt', [
            'id' => $id,
            'error' => $e->getMessage()
        ]);

        return back()
            ->withInput()
            ->with('error', 'Error 400 - Ongeldige invoer.');
    }
}
   // Delete a vehicle from the system
public function destroy(int $id): RedirectResponse
{
    try {

        $auto = Auto::GetAutoById($id);

        abort_if(is_null($auto), 404, 'Geen voertuigen gevonden');

        Auto::DeleteAuto($id);

        return redirect()
            ->route('autos.index')
            ->with('success', 'Auto succesvol verwijderd.');

    } catch (\Exception $e) {

        Log::error('Auto verwijderen mislukt', [
            'id' => $id,
            'error' => $e->getMessage()
        ]);

        return redirect()
            ->route('autos.index')
            ->with('error', 'Verwijderen mislukt.');
    }
}
}
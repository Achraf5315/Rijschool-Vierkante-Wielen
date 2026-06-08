<?php

namespace App\Http\Controllers;

use App\Http\Requests\DrivingPackageRequest;
use App\Models\DrivingPackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

/**
 * Controller: DrivingPackageController (Lesrijpakketten)
 *
 * Bevat de volledige CRUD voor lesrijpakketten:
 *  - index   : overzicht (gastgebruiker ziet actieve pakketten, admin ziet alles)
 *  - create  : formulier om een nieuw pakket toe te voegen (admin)
 *  - store   : nieuw pakket opslaan (admin)
 *  - edit    : formulier om een bestaand pakket te bewerken (admin)
 *  - update  : wijzigingen opslaan (admin)
 *  - destroy : pakket verwijderen (admin)
 *
 * De index is openbaar; de overige acties worden in de routes afgeschermd met
 * de `role:admin` middleware. De foutmeldingen en bevestigingen worden in de
 * views getoond als echte UI-overlays (flash-banner), niet als browser-popup.
 */
class DrivingPackageController extends Controller
{
    /**
     * Openbaar overzicht van lesrijpakketten.
     *
     * Iedereen (ook gastgebruikers) ziet hier de beschikbare, oftewel actieve
     * pakketten. Het beheren van pakketten gebeurt op het dashboard.
     */
    public function index(): View
    {
        $packages = DrivingPackage::active()->orderBy('Price')->get();

        return view('driving-packages.index', [
            'packages' => $packages,
        ]);
    }

    /**
     * Aparte beheerpagina "Lesrijpakketten beheren" voor admin/instructeur.
     *
     * Toont alle pakketten (actief eerst, daarna inactief) met knoppen om ze
     * toe te voegen, te bewerken of te verwijderen.
     */
    public function manage(): View
    {
        $packages = DrivingPackage::orderByDesc('IsActive')->orderBy('Name')->get();

        return view('driving-packages.manage', [
            'packages' => $packages,
        ]);
    }

    /**
     * Toon het formulier om een nieuw lesrijpakket toe te voegen.
     */
    public function create(): View
    {
        return view('driving-packages.create');
    }

    /**
     * Sla een nieuw lesrijpakket op.
     *
     * De gegevens zijn al gevalideerd door DrivingPackageRequest. Mocht het
     * opslaan onverhoopt misgaan, dan vangen we dat netjes op en tonen we een
     * foutmelding in plaats van een witte/technische foutpagina.
     */
    public function store(DrivingPackageRequest $request): RedirectResponse
    {
        try {
            DrivingPackage::create($request->validated());
        } catch (Throwable $e) {
            // Log de technische fout en stuur de gebruiker terug met een nette melding.
            Log::error('Lesrijpakket opslaan mislukt: '.$e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Het lesrijpakket kon niet worden opgeslagen. Probeer het opnieuw.');
        }

        return redirect()
            ->route('driving-packages.manage')
            ->with('success', 'Het lesrijpakket is toegevoegd en zichtbaar in het overzicht.');
    }

    /**
     * Toon het formulier om een bestaand lesrijpakket te bewerken.
     *
     * Route model binding zoekt het pakket op via de primary key `Id`.
     */
    public function edit(DrivingPackage $drivingPackage): View
    {
        return view('driving-packages.edit', [
            'package' => $drivingPackage,
        ]);
    }

    /**
     * Sla de wijzigingen aan een bestaand lesrijpakket op.
     */
    public function update(DrivingPackageRequest $request, DrivingPackage $drivingPackage): RedirectResponse
    {
        try {
            $drivingPackage->update($request->validated());
        } catch (Throwable $e) {
            Log::error('Lesrijpakket bijwerken mislukt: '.$e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Het lesrijpakket kon niet worden bijgewerkt. Probeer het opnieuw.');
        }

        return redirect()
            ->route('driving-packages.manage')
            ->with('success', 'Het lesrijpakket is bijgewerkt.');
    }

    /**
     * Verwijder een lesrijpakket.
     */
    public function destroy(DrivingPackage $drivingPackage): RedirectResponse
    {
        $drivingPackage->delete();

        return redirect()
            ->route('driving-packages.manage')
            ->with('success', 'Het lesrijpakket is verwijderd.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\DrivingPackage;

/**
 * Controller: HomeController
 *
 * Verantwoordelijk voor de publieke homepagina. Een gastgebruiker ziet hier de
 * informatie over de rijschool en een paar voorbeeld-lesrijpakketten met hun
 * prijzen. Wanneer er onderhoud aan de website plaatsvindt, tonen we in plaats
 * van de homepagina een nette onderhoudsmelding (overlay).
 */
class HomeController extends Controller
{
    /**
     * Toon de homepagina, of de onderhoudsmelding als de website in
     * onderhoudsmodus staat (zie config/rijschool.php).
     */
    public function index()
    {
        // Scenario "Home pagina tijdelijk niet beschikbaar": bij onderhoud
        // tonen we een melding-pagina met HTTP-status 503 (Service Unavailable).
        if (config('rijschool.maintenance')) {
            return response()
                ->view('maintenance', [], 503);
        }

        // Een paar actieve pakketten als voorbeeld op de homepagina, gesorteerd
        // op prijs zodat de bezoeker meteen ziet hoe duur de pakketten kunnen zijn.
        $featuredPackages = DrivingPackage::active()
            ->orderBy('Price')
            ->take(3)
            ->get();

        return view('home', [
            'featuredPackages' => $featuredPackages,
        ]);
    }
}

<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Onderhoudsmodus homepagina
    |--------------------------------------------------------------------------
    |
    | Wanneer dit op `true` staat, toont de homepagina een nette overlay/melding
    | dat de website tijdelijk niet beschikbaar is vanwege onderhoud. Schakel in
    | of uit via de variabele HOME_MAINTENANCE in het .env bestand.
    |
    */

    'maintenance' => env('HOME_MAINTENANCE', false),

];

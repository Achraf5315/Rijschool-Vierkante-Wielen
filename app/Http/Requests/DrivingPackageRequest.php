<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Form Request: DrivingPackageRequest
 *
 * Valideert de gegevens van een lesrijpakket bij zowel het toevoegen (store)
 * als het bewerken (update). Door één request voor beide acties te gebruiken
 * blijven de validatieregels op één plek staan (DRY).
 *
 * Wanneer verplichte velden ontbreken, stuurt Laravel de gebruiker automatisch
 * terug met de foutmeldingen in de sessie. De view toont die meldingen als
 * echte UI-overlay/banner (geen browser-popup).
 */
class DrivingPackageRequest extends FormRequest
{
    /**
     * Alleen een ingelogde administrator of instructeur mag pakketten toevoegen
     * of wijzigen. De routes zijn ook met de `role:admin,instructor` middleware
     * beschermd; dit is een extra controle op aanvraagniveau.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        return $user !== null && $user->canManagePackages();
    }

    /**
     * De validatieregels voor een lesrijpakket.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'Name' => ['required', 'string', 'max:150'],
            'Description' => ['required', 'string', 'max:255'],
            'LessonCount' => ['required', 'integer', 'min:1', 'max:200'],
            'LessonDuration' => ['required', 'integer', 'min:15', 'max:240'],
            // Prijs mag niet negatief en niet onrealistisch hoog zijn.
            'Price' => ['required', 'numeric', 'min:0', 'max:100000'],
            'Category' => ['required', 'string', 'max:10'],
            'IsActive' => ['boolean'],
            'Notes' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Zorg dat de checkbox "IsActive" altijd een boolean is voordat we
     * valideren (een niet-aangevinkte checkbox wordt niet meegestuurd).
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'IsActive' => $this->boolean('IsActive'),
        ]);
    }

    /**
     * Nederlandse, leesbare namen voor de velden in foutmeldingen.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'Name' => 'naam',
            'Description' => 'omschrijving',
            'LessonCount' => 'aantal lessen',
            'LessonDuration' => 'lesduur',
            'Price' => 'prijs',
            'Category' => 'categorie',
            'Notes' => 'notities',
        ];
    }

    /**
     * Nederlandse foutmeldingen, o.a. voor het "verplichte velden ontbreken"-scenario.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => 'Het veld :attribute is verplicht.',
            'integer' => 'Het veld :attribute moet een geheel getal zijn.',
            'numeric' => 'Het veld :attribute moet een getal zijn.',
            // Voor min/max gebruikt Laravel aparte meldingen per veldtype, zodat
            // getalvelden "te hoog/te laag" tonen i.p.v. "te lang".
            'min' => [
                'numeric' => 'Het veld :attribute mag niet lager zijn dan :min.',
                'string' => 'Het veld :attribute moet minimaal :min tekens bevatten.',
            ],
            'max' => [
                'numeric' => 'Het veld :attribute mag niet hoger zijn dan :max.',
                'string' => 'Het veld :attribute mag niet langer zijn dan :max tekens.',
            ],
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\DrivingLesson;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class DrivingLessonController extends Controller
{
    /**
     * Show the driving lessons overview.
     */
    public function index(DrivingLesson $drivingLesson): View
    {
        return view('driving-lessons.index', [
            'lessons' => $drivingLesson->getAllDrivingLessons(),
        ]);
    }

    /**
     * Show the create lesson form.
     */
    public function create(DrivingLesson $drivingLesson): View
    {
        return view('driving-lessons.create', [
            'clients' => $drivingLesson->getActiveClients(),
            'instructors' => $drivingLesson->getActiveInstructors(),
            'vehicles' => $drivingLesson->getActiveVehicles(),
        ]);
    }

    /**
     * Store a new driving lesson.
     */
    public function store(Request $request, DrivingLesson $drivingLesson): RedirectResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'client_id' => [
                    'required',
                    'integer',
                    Rule::exists('Client', 'Id')->where(static function ($query) {
                        $query->where('IsActive', 1);
                    }),
                ],
                'instructor_id' => [
                    'required',
                    'integer',
                    Rule::exists('Instructor', 'Id')->where(static function ($query) {
                        $query->where('IsActive', 1);
                    }),
                ],
                'vehicle_id' => [
                    'required',
                    'integer',
                    Rule::exists('Vehicle', 'Id')->where(static function ($query) {
                        $query->where('IsActive', 1);
                    }),
                ],
                'start_time' => ['required', 'date_format:Y-m-d\TH:i'],
                'end_time' => ['required', 'date_format:Y-m-d\TH:i'],
                'location' => ['required', 'string', 'max:255'],
            ], [
                'required' => 'Dit veld is verplicht.',
                'integer' => 'Selecteer een geldige optie.',
                'exists' => 'Selecteer een geldige optie.',
                'date_format' => 'Gebruik een geldige datum en tijd.',
                'max' => 'De ingevoerde tekst is te lang.',
            ]);

            $validator->after(static function ($validator) use ($request): void {
                $startTime = $request->input('start_time');
                $endTime = $request->input('end_time');

                if ($startTime === null || $endTime === null) {
                    return;
                }

                try {
                    $parsedStartTime = Carbon::createFromFormat('Y-m-d\TH:i', $startTime);
                    $parsedEndTime = Carbon::createFromFormat('Y-m-d\TH:i', $endTime);
                } catch (Throwable $throwable) {
                    $validator->errors()->add('end_time', 'Gebruik een geldige datum en tijd.');

                    return;
                }

                if ($parsedStartTime->greaterThanOrEqualTo($parsedEndTime)) {
                    $validator->errors()->add('end_time', 'Eindtijd moet na starttijd liggen.');
                }
            });

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Niet alle gegevens zijn correct ingevuld.');
            }

            $validated = $validator->validated();
            $startTime = Carbon::createFromFormat('Y-m-d\TH:i', $validated['start_time'])->format('Y-m-d H:i:s');
            $endTime = Carbon::createFromFormat('Y-m-d\TH:i', $validated['end_time'])->format('Y-m-d H:i:s');

            $drivingLesson->addDrivingLesson([
                'client_id' => $validated['client_id'],
                'instructor_id' => $validated['instructor_id'],
                'vehicle_id' => $validated['vehicle_id'],
                'start_time' => $startTime,
                'end_time' => $endTime,
                'location' => $validated['location'],
                'notes' => $validated['location'],
            ]);

            return redirect()
                ->route('driving-lessons.index')
                ->with('success', 'Rijles succesvol toegevoegd!');
        } catch (Throwable $e) {
            error_log('DrivingLessonController@store: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Er is iets misgegaan bij het opslaan van de rijles.');
        }
    }

    /**
     * Placeholder action for the edit button.
     */
    public function edit(int $lesson): RedirectResponse
    {
        return redirect()
            ->route('driving-lessons.index')
            ->with('error', 'Wijzigen is nog niet beschikbaar.');
    }

    /**
     * Placeholder action for the delete button.
     */
    public function destroy(int $lesson): RedirectResponse
    {
        return redirect()
            ->route('driving-lessons.index')
            ->with('error', 'Verwijderen is nog niet beschikbaar.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Enums\SportType;
use App\Models\Sport;
use App\Services\KnockoutBracketService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function __construct(private readonly KnockoutBracketService $bracketService) {}

    public function show(Request $request, string $sportName): View
    {
        $sport = Sport::query()
            ->where('name', $sportName)
            ->where('type', SportType::League)
            ->firstOrFail();
        $availableGenders = collect([
            Gender::Male->value => $sport->male_quota,
            Gender::Female->value => $sport->female_quota,
        ])->filter(fn (int $quota) => $quota > 0)->keys()->map(fn (string $gender) => Gender::from($gender));
        $requestedGender = Gender::tryFrom((string) $request->input('gender'));
        $gender = $availableGenders->contains($requestedGender)
            ? $requestedGender
            : $availableGenders->first();

        return view('knockout.index', [
            'sports' => collect([$sport]),
            'selectedSport' => $sport,
            'gender' => $gender,
            'availableGenders' => $availableGenders,
            'singleEventPage' => true,
            ...$this->bracketService->for($sport, $gender),
        ]);
    }
}

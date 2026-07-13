<?php

namespace App\Services;

use App\Models\Participant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ParticipantService
{
    public function paginate(array $filters = []): LengthAwarePaginator
    {
        $query = Participant::query()->with('house')->orderBy('name');

        if ($search = Arr::get($filters, 'search')) {
            $query->where(function ($builder) use ($search): void {
                $builder->where('name', 'like', '%'.$search.'%')
                    ->orWhere('employee_no', 'like', '%'.$search.'%')
                    ->orWhere('department', 'like', '%'.$search.'%');
            });
        }

        if ($houseId = Arr::get($filters, 'house_id')) {
            $query->where('house_id', $houseId);
        }

        if ($gender = Arr::get($filters, 'gender')) {
            $query->where('gender', $gender);
        }

        return $query->paginate(10)->withQueryString();
    }

    public function create(array $data): Participant
    {
        return Participant::query()->create($data);
    }

    public function publicListing(array $filters = []): Collection
    {
        return Participant::query()
            ->with(['house', 'sports'])
            ->whereHas('sports')
            ->when(Arr::get($filters, 'search'), function ($query, string $search): void {
                $query->where('name', 'like', '%'.$search.'%');
            })
            ->when(Arr::get($filters, 'house_id'), function ($query, string $houseId): void {
                $query->where('house_id', $houseId);
            })
            ->when(Arr::get($filters, 'gender'), function ($query, string $gender): void {
                $query->where('gender', $gender);
            })
            ->when(Arr::get($filters, 'sport_id'), function ($query, string $sportId): void {
                $query->whereHas('sports', fn ($sportQuery) => $sportQuery->whereKey($sportId));
            })
            ->orderBy('house_id')
            ->orderBy('name')
            ->get();
    }

    public function update(Participant $participant, array $data): Participant
    {
        $participant->update($data);

        return $participant;
    }

    public function delete(Participant $participant): void
    {
        $participant->delete();
    }
}

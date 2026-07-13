<?php

namespace App\Services;

use App\Models\Participant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

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
<?php

namespace App\Actions\Jetstream;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\UpdatesTeamNames;

class UpdateTeamName implements UpdatesTeamNames
{
    /**
     * Validate and update the given team's name.
     *
     * @param  mixed  $user
     * @param  mixed  $team
     * @param  array  $input
     * @return void
     */
    public function update($user, $team, array $input)
    {
        Gate::forUser($user)->authorize('update', $team);

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'manager' => ['string', 'max:255'],
            'phone' => ['string', 'max:255'],
            'email' => ['email'],
        ])->validateWithBag('updateTeamName');

        $team->forceFill([
            'name' => $input['name'],
            'manager' => $input['manager'],
            'phone' => $input['phone'],
            'email' => $input['email'],
            'address' => $input['address'],
        ])->save();
    }
}

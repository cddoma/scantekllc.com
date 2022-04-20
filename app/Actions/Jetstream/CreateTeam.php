<?php

namespace App\Actions\Jetstream;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Events\AddingTeam;
use Laravel\Jetstream\Jetstream;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateTeam implements CreatesTeams
{
    /**
     * Validate and create a new team for the given user.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return mixed
     */
    public function create($user, array $input)
    {
        Gate::forUser($user)->authorize('create', Jetstream::newTeamModel());

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'owner' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
        ])->validateWithBag('createTeam');

        AddingTeam::dispatch($user);

        $new_user = User::firstOrCreate(
            ['email' => $input['email']],
            [
                'name' => $input['owner'],
                'password' => Hash::make('password1')//Hash::make(Str::random(32)),
            ]
        );

        $team = Team::create([
            'name' => $input['name'],
            'user_id' => $new_user->id,
            'personal_team' => false,
        ]);
        Password::sendResetLink(['email' => $new_user->email]);

        $new_user->switchTeam($team);

        return $team;
    }

    public function redirectTo()
    {
        return route('teams.show', \Auth::user()->current_team_id);
    }
}

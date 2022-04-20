<?php

namespace App\Actions\Fortify;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:10240'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            // resize the image to a width of 320 and constrain aspect ratio (auto height)
            $user->updateProfilePhoto($input['photo']);
            $og_path = storage_path('app/public').'/';
            $path = $og_path . $user->profile_photo_path;
            $img = \Image::make($path)->orientate();
            if($img->width() > 400 || $img->height() > 400) {
                if(!is_dir($og_path.'original/')) {
                    mkdir($og_path.'original/');
                    if(!is_dir($og_path.'original/profile-photos/')) {
                        mkdir($og_path.'original/profile-photos/');
                    }
                }
                $img->save($og_path.'original/'.$user->profile_photo_path);
                $img->resize(400, 400, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $img->save($path);
            }
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    protected function updateVerifiedUser($user, array $input)
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Enums\Verification\VerificationAbility;
use App\Exceptions\Api\v1\Identity\IdentityNotFound;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\ForgotPassword;
use App\Http\Resources\Api\v1\Verification\VerificationResource;
use App\Models\{ Identity, Verification };

class ForgotPasswordController extends Controller
{
    /**
     * Start an account recovery for the given account identity.
     *
     * @param  \App\Http\Requests\Api\v1\Auth\ForgotPassword  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ForgotPassword $request)
    {
        $fields = $request->validated();
        $identity = Identity::findFromFields($fields);

        if (! $identity) {
            throw new IdentityNotFound;
        }

        $verification = Verification::create([
            'ability' => VerificationAbility::Recover,
            'verifiable_id' => $identity->id,
            'verifiable_type' => Identity::class,
        ]);

        return response(new VerificationResource($verification), 201);
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SsoController extends Controller
{
    // /login/sso → redirect to IdP
    public function redirect(Request $request)
    {
        if (! config('sso.enabled')) {
            // In dev, just send back to normal login
            return redirect()->route('login')->with('status', 'SSO is not enabled yet.');
        }

        if (config('sso.driver') === 'saml') {
            return redirect()->route('saml2_login'); // from laravel-saml2
        }

        abort(404);
    }

    // This is here mainly for future OIDC; with SAML we’ll use the package event.
    public function callback(Request $request)
    {
        abort(404);
    }

    // Helper you can reuse when wiring SAML events
    public static function loginFromClaims(array $claims): User
    {
        $externalId = data_get($claims, config('sso.map.external_id'));
        $email      = data_get($claims, config('sso.map.email'));
        $name       = data_get($claims, config('sso.map.name'));
        $role       = data_get($claims, config('sso.map.role')) ?: 'agent';

        $user = User::firstOrCreate(
            ['external_id' => $externalId, 'idp' => 'adfs'],
            ['email' => $email, 'name' => $name ?? $email ?? 'User', 'role' => $role]
        );

        // Keep basic info updated
        $user->update([
            'email' => $email ?? $user->email,
            'name'  => $name  ?? $user->name,
        ]);

        Auth::login($user, remember: true);

        return $user;
    }
}

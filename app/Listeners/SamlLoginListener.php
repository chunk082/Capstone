<?php 

namespace App\Listeners;

use Aacotroneo\Saml2\Events\Saml2LoginEvent;
use App\Http\Controllers\Auth\SsoController;
use Illuminate\Support\Facades\Auth;

class SamlLoginListener
{
    public function handle(Saml2LoginEvent $event): void
    {
        if (! config('sso.enabled') || config('sso.driver') !== 'saml') return;

        $samlUser = $event->getSaml2User();
        $nameId   = $samlUser->getNameId();
        $attrs    = $samlUser->getAttributes();

        $flat = collect($attrs)->map(fn ($v) => is_array($v) ? ($v[0] ?? null) : $v)->all();

        $claims = array_merge(['nameid' => $nameId], $flat);

        $user = SsoController::loginFromClaims($claims);

        $event->setIntendedUrl(url('/'));
        Auth::login($user, remember: true);
    }
}

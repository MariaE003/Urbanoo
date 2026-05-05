<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    //la rediriger vers google
     
    public function redirectToGoogle(){
        return Socialite::driver('google')->redirect();
    }

    //retour de google(callback)
    public function handleGoogleCallback(){
        try {
            //recupere les infos de google
            $googleUser = Socialite::driver('google')->stateless()->user();
            // verifier si user existe deja ou non
            $user = User::where('email', $googleUser->email)->first();
            if (!$user) {
                // creation du user si n'existe pas
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => bcrypt(Str::random(16)), //mot de passe aleatoire
                    'role' => 'citizen'
                ]);
            }

            if (!$user->is_active) {
                return redirect('/login')->with('error', 'votre compte est desactive');
            }
            // connecter user
            Auth::login($user);
            return redirect('/');

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'erreur google login');
        }
    }
}

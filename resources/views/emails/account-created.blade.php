@component('mail::message')
# Bienvenue, {{ $user->name }}

Votre compte invité a été créé avec succès.

Voici vos identifiants :

- **Email :** {{ $user->email }}
- **Mot de passe :** {{ $plainPassword }}

@component('mail::button', ['url' => url('/login')])
Se connecter
@endcomponent

Merci et à bientôt,<br>
L'équipe {{ config('app.name') }}
@endcomponent
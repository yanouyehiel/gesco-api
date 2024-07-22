<x-mail::message>
# Modifier le password

Veuillez cliquer s'il vous plait sur le bouton ci-dessous pour modifier votre mot de passe.

<x-mail::button :url="$url">
Reset Password
</x-mail::button>

L'équipe {{ config('app.name') }},<br>
Cordialement
</x-mail::message>

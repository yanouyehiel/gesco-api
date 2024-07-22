<x-mail::message>
# Email Verification

Veuillez cliquer s'il vous plait sur le bouton ci-desosus pour vérifier votre adresse email

<x-mail::button :url="$url">
Vérifier l'email
</x-mail::button>

L'équipe {{ config('app.name') }},<br>
Cordialement
</x-mail::message>

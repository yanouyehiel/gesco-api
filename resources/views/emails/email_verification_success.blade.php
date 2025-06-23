<x-mail::message>
# Email Verification Success

Félicitations!! <br>
La vérification de l'email de votre compte sur GESCO s'est bien passé.

<x-mail::button :url="$url">
Se connecter
</x-mail::button>

L'équipe {{ config('app.name') }},<br>
Cordialement
</x-mail::message>

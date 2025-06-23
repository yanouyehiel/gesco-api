<x-mail::message>
# Modification du password

{{ $user->nom . ' ' .$user->prenom }},<br> 
Votre mot de passe vient d'être modifié avec succès !

L'équipe {{ config('app.name') }},<br>
Cordialement
</x-mail::message>

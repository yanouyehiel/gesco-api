<?php

namespace App\Services;

use App\Models\NotificationInformations;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\RequestException;

class NotificationService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://exp.host/--/api/v2/push/',
            'timeout'  => 5.0,
        ]);
    }

    /**
     * Envoie une notification push via Expo
     * 
     * @param string $expoPushToken
     * @param string $title
     * @param string $body
     * @param array $data Données personnalisées optionnelles
     * @return array|null
     */
    public function sendNotification(
        string $expoPushToken, 
        string $title, 
        string $body, 
        string $type,
        int $user_id,
        array $data = [])
    {
        $payload = [
            'to' => $expoPushToken,
            'sound' => 'default',
            'title' => $title,
            'body' => $body,
            'type' => $type,
            'user_id' => $user_id
        ];

        try {
            $response = $this->client->post('send', [
                'json' => $payload,
                //'debug' => true,
                'verify' => false,
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            $notification = new NotificationInformations();
            $notification->title = $title;
            $notification->token = $expoPushToken;
            $notification->content = $body;
            $notification->type = $type;
            $notification->user_id = $user_id;
            $notification->save();

            $responseContent = $response->getBody()->getContents();
            Log::info('Notification sended : '.$responseContent);

            return json_decode($responseContent, true);
        } catch (RequestException $e) {
            // Log l’erreur ou gérer l’exception
            Log::error('Erreur envoi notification Expo: '.$e->getMessage());

            // Si la réponse est disponible, logue son contenu (souvent utile)
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $body = (string) $response->getBody();
                Log::error('Réponse API : ' . $body);
            }

            return null;
        }
    }

    /**
     * Récupère les notifications d'un utilisateur connecté
     * 
     * @param int $user_id
     * @return array|null
     */
    public function getNotificationsUser($user_id) 
    {
        return NotificationInformations::where('user_id', $user_id)->get();
    }
}
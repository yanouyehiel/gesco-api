<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    protected $expoService;

    public function __construct(NotificationService $expoService)
    {
        $this->expoService = $expoService;
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'title' => 'required|string',
            'body' => 'required|string',
            'type' => 'required|string',
        ]);

        $result = $this->expoService->sendNotification(
            $request->input('token'),
            $request->input('title'),
            $request->input('body'),
            $request->input('type'),
            $request->user()->id
        );

        if ($result) {
            return response()->json(['message' => 'Notification envoyée', 'result' => $result]);
        }

        return response()->json([
            'message' => 'Erreur lors de l\'envoi de la notification',
            'error' => $result
        ], 500);
    }

    public function getNotificationsUser(Request $request) {
        $result = $this->expoService->getNotificationsUser($request->user()->id);
        return response()->json($result, 200);
    }
}
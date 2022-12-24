<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RasaController extends Controller
{
	public static function askJarvis($ask, $sender) {
        $response = Http::withHeaders([
            'Content-type' => 'application/json',
            'Accept' => 'application/json'
        ])->post("192.168.0.161:5005/webhooks/rest/webhook", [
            'sender' => $sender,
            'message' => $ask
        ]);
        
        $body = json_decode($response->getBody(), true);
        if (count($body) === 0){
            return "I'm confused";
        }
        else {
            return $body;
        }
	}
}
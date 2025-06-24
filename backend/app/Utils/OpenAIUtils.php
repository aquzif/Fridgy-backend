<?php

namespace App\Utils;

use OpenAI;

class OpenAIUtils {

    public static function prompt($prompt) {

        $token = env('OPENAI_API_KEY');
        $client = OpenAI::client($token);

        $response = $client->chat()->create([
            'model' => 'gpt-4o',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        foreach ($response->choices as $result) {
            $response = $result->message->content;
            return response($response, 201);
        }

    }

}

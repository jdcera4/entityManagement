<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Utils\Utils;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ProcessGtpController extends Controller
{
  // Enunciados para hacer el llamado a GPT
  const STATAMENTS = [
    'users' => "generate a list of 10 users in JSON format with the following fields: name, email, image_path, password",
    'companies' => "generate a list of 10 companies in JSON format with the following fields: name, image_path, location, industry, user_id consecutive 1 to 10",
    'challenges' => "generate a list of 10 challenges in JSON format with the following fields: title, description, integer for difficulty, user_id consecutive of 1 to 10",
    'programs' => "generate a list of 10 academic programs in JSON format with the following fields: title, description, start_date format yyyy-mm-dd, end_date format yyyy-mm-dd, user_id consecutive of 1 to 10",
  ];

  /**
   * Action to get data for process in table
   *
   * @param Request $request
   */
  public function data(Request $request)
  {
    $model = $request->query('model');
    if (isset($model) && $model != '') {
      $statament = self::STATAMENTS[$model];
      if ($statament) {
        try {
          $client = new Client();
          $response = $client->post(config('services.gpt.url'), [
            'headers' => [
              'Authorization' => 'Bearer ' . config('services.gpt.key'),
            ],
            'json' => [
              "model" => "gpt-3.5-turbo-1106",
              "response_format" => ["type" => "json_object"],
              "messages" => [
                [
                  "role" => "user",
                  "content" => $statament
                ]
              ]
            ]
          ]);

          $result = json_decode($response->getBody(), true);
          $content = $result["choices"][0]["message"]["content"] ?? "";
          $data = json_decode($content, true);
          $total_save =Utils::processInformationTable($model, $data);

          return response()->json([
            'success' => true,
            'message' => "{$total_save} records were saved"
          ]);
        } catch (\Throwable $th) {
          return response()->json($th->getMessage());
        }
      }
    }
  }
}

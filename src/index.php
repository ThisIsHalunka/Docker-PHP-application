<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;

try {
    // Створюємо нового клієнта Guzzle
    $client = new Client();

    // Здійснюємо GET запит до прикладного API
    $response = $client->request('GET', 'https://jsonplaceholder.typicode.com/todos/1');

    // Отримуємо статусний код відповіді
    $statusCode = $response->getStatusCode();

    // Отримуємо тіло відповіді
    $body = $response->getBody();

    // Декодуємо JSON відповідь
    $data = json_decode($body, true);

    // Виводимо отримані дані
    echo "Status Code: " . $statusCode . PHP_EOL;
    echo "Response Body: " . PHP_EOL;
    print_r($data);

} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
?>

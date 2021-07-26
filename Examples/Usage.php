<?php
include("vendor\autoload.php");

use Sidn\Suggestion\Api\Exceptions\ApiException;
use  \Sidn\Suggestion\Api\SidnSuggestionApiClient;

try {
    // Create new instance of SidnSuggestionApiClient
    $sidnApi = new SidnSuggestionApiClient();

    // Authenticate using Client Id and Client Secret provided by SIDN
    $auth = $sidnApi->authenticate->Authenticate($client_id, $client_secret);
    $sidnApi->setAccessToken($auth->access_token);

    // Search for domain suggestions
    $suggestions = $sidnApi->suggestion->Search("bike", 1000);
    print_r($suggestions->suggestions);

    // Optionally, query the used configuration from the results
    print_r($suggestions->config);

    // Optionally, query the used domain (cleaned) from the results
    print_r($suggestions->original);
} catch (ApiException $ae) {
    throw $ae;
}
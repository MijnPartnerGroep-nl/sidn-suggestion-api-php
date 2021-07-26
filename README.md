# SIDN Suggestion API - PHP client
PHP client for SIDN suggestion API

## Requirements and Authorization ##
To use the API you must request access by sending a request to support@sidn.nl or contacting your SIDN representative. When approved 
you will be provided with access to the API. The API uses the Client Authorization flow for server-side authorization. To start the authorization flow, you need to request a clientId and clientSecret. You can request these from SIDN Support. 

## Installation ##
The easiest way to install this client is to require it using [Composer](http://getcomposer.org/doc/00-intro.md).

Install using `composer require MijnPartnerGroep-nl/sidn-suggestion-api-php:^0.1`

Or add to your composer.json:
```JSON
{ 
    "require": {
        "MijnPartnerGroep-nl/sidn-suggestion-api-php": "^0.1"
    }
}
```

## Example usage ##
Example usage as found in [the examples folder](examples/usage.php).
```PHP
<?php
include("vendor\autoload.php");

use \Sidn\Suggestion\Api\Exceptions\ApiException;
use  \Sidn\Suggestion\Api\SidnSuggestionApiClient;

try {
    // Create new instance of SidnSuggestionApiClient
    $sidnApi = new SidnSuggestionApiClient();

    // Authenticate using Client Id and Client Secret provided by SIDN
    $auth = $sidnApi->authenticate->Authenticate($client_id, $client_secret);
    $sidnApi->setAccessToken($auth->access_token);

    // Search for domain suggestions
    $suggestions = $sidnApi->suggestion->Search("bike.nl", 1000);
    print_r($suggestions->suggestions);

    // Optionally, query the used configuration from the results
    print_r($suggestions->config);

    // Optionally, query the used domain (cleaned) from the results
    print_r($suggestions->original);
} catch (ApiException $ae) {
    throw $ae;
}
```` 

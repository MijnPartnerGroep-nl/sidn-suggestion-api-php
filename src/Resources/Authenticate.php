<?php

namespace Sidn\Suggestion\Api\Resources;

use Sidn\Suggestion\Api\Exceptions\ApiException;

/**
 * Resources: Authenticate
 */
class Authenticate extends BaseResource
{

    /**
     * @var string The Access Token can be used to access the API by providing it in the Authorization header
     */
    public $access_token;
    /**
     * @var string Bearer
     */
    public $token_type;
    /**
     * @var int Timestamp, time in microseconds in which the token will expire
     */
    public $expires_in;
    /**
     * @var string $scope must include suggestions
     */
    public $scope = "suggestions";

    /**
     * Predefined whitelist of expected properties
     *
     * @var array
     */
    public $expectedProperties = ["access_token", "token_type", "expires_in", "scope", "jti"];

    /**
     * This API uses the Client Authorization flow for server-side authorization. To start the authorization flow,
     * you need to request a clientId and clientSecret. You can request these from SIDN Support.
     *
     * @param string $client_id Client Id as provided by SIDN
     * @param string $client_secret Client Secret as provided by SIDN
     * @param string $scope Scope – must include suggestions
     * @param string $grant_type Use the Client Authorization flow for server-side authorization
     */
    public function authenticate($client_id, $client_secret, $scope = "suggestions", $grant_type = "client_credentials")
    {
        if (empty($client_id) || empty($client_secret)) {
            throw new ApiException("Both client_id and client_secret are required!");
        }

        $result = $this->client->ep_authenticate->post([
            "grant_type" => $grant_type,
            "scope" => $scope
        ], [
            "Authorization" => "Basic " . base64_encode($client_id . ":" . $client_secret)
        ]);

        return ResourceFactory::resourceFromResult($result, new static($this->client));
    }
}

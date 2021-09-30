<?php

namespace Sidn\Suggestion\Api\Endpoints;

use Sidn\Suggestion\Api\SidnSuggestionApiClient;
use Sidn\Suggestion\Api\Resources\ResourceFactory;

/**
 * Endpoints: EndpointAbstract
 */
abstract class EndpointAbstract
{
    /**
     * @var \Sidn\Suggestion\Api\SidnSuggestionApiClient Client
     */
    protected $client;

    /**
     * @var string
     * The endpoint used in the URL
     */
    protected $resourceEndpoint;

    /**
     * EndpointAbstract
     *
     * @param \Sidn\Suggestion\Api\SidnSuggestionApiClient $api
     */
    public function __construct(SidnSuggestionApiClient $api)
    {
        $this->client = $api;
    }

    /**
     * Send GET request to API using provided parameters
     *
     * @param array $params Parameters to be used in the URL of the request
     * @return \Sidn\Suggestion\Api\Resources\BaseResource
     * @throws \Sidn\Suggestion\Api\Exceptions\ApiException
     */
    public function get(array $params)
    {
        $result = $this->client->sendHttpRequest(
            "GET",
            $this->resourceEndpoint . "/" . $this->createQueryString($params)
        );
        return ResourceFactory::resourceFromResult($result, $this->getResourceObject());
    }

    /**
     * Send POST request using provided request body and optional headers
     *
     * @param array $body Parameters to be used as the body of the request
     * @param array $headers Optional headers to be send with the request
     * @return \Sidn\Suggestion\Api\Resources\BaseResource
     * @throws \Sidn\Suggestion\Api\Exceptions\ApiException
     */
    public function post(array $body, array $headers = [])
    {
        $headers = array_merge($headers, ["Content-Type" => "application/x-www-form-urlencoded"]);
        $result = $this->client->sendHttpRequest(
            "POST",
            $this->resourceEndpoint,
            count($body) > 0 ? $this->createQueryString($body, false) : null,
            $headers
        );
        return ResourceFactory::resourceFromResult($result, $this->getResourceObject());
    }

    /**
     * Create query string from provided array
     *
     * @param array $params Parameters to be converted to a query string
     * @param bool $get Optional, defaults to true assuming this is used for a GET request
     * @return string
     * @throws \Sidn\Suggestion\Api\Exceptions\ApiException
     */
    protected function createQueryString(array $params, bool $get = true)
    {
        return count($params) > 0 ? ($get ? "?" : "") . http_build_query($params) : "";
    }

    /**
     * Get the object that is used by this API endpoint.
     *
     * @return \Sidn\Suggestion\Api\Resources\BaseResource
     */
    abstract protected function getResourceObject();
}

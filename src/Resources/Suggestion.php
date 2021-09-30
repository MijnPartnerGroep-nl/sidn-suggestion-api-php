<?php

namespace Sidn\Suggestion\Api\Resources;

use Sidn\Suggestion\Api\Exceptions\ApiException;

/**
 * Resources: Suggestion
 */
class Suggestion extends BaseResource
{

    /**
     * @var string Contains the original domain name (cleaned)
     */
    public $original;
    /**
     * @var array Array of configurations used to determine the provided suggestions
     */
    public $config;
    /**
     * @var array Array with suggestions based on the domain
     */
    public $suggestions;

    /**
     * Predefined whitelist of expected properties
     *
     * @var array
     */
    public $expectedProperties = ["original", "config", "suggestions"];

    /**
     * Searching for a domain will return relevant and available domain name suggestions.
     *
     * @param string $domain The domain name which used as input to find suggestions (required)
     * @param int $limit The max (default 100) number of suggestions in the response, (optional)
     * @return \Sidn\Suggestion\Api\Resources\Suggestion
     * @throws \Sidn\Suggestion\Api\Exceptions\ApiException
     */
    public function search(string $domain, int $limit = 100)
    {
        if (empty($domain)) {
            throw new ApiException("A domain is required for this method!");
        }

        $result = $this->client->ep_suggestion->get([
            "domain" => $domain,
            "limit" => $limit
        ]);

        return ResourceFactory::resourceFromResult($result, new static($this->client));
    }
}

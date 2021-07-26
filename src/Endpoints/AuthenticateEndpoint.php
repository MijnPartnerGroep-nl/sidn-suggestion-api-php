<?PHP

namespace Sidn\Suggestion\Api\Endpoints;

use Sidn\Suggestion\Api\Resources\Authenticate;


class AuthenticateEndpoint extends EndpointAbstract
{
    /**
     * @var string Endpoint
     */
    protected $resourceEndpoint = "oauth/token";
    /**
     * @var \Sidn\Suggestion\Api\Resources\Authenticate
     */
    protected $resourceClass = Authenticate::class;

    /**
     * Initiate instance of endpoint object
     * 
     * @return \Sidn\Suggestion\Api\Resources\Authenticate
     */
    public function initiate() {
        return $this->getResourceObject();
    }

    /**
     * Get the object that is used by this API endpoint.
     *
     * @return \Sidn\Suggestion\Api\Resources\Authenticate
     */
    protected function getResourceObject()
    {
        return new $this->resourceClass($this->client);
    }
}

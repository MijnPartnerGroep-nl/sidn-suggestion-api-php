<?PHP

namespace Sidn\Suggestion\Api\Endpoints;

use Sidn\Suggestion\Api\Resources\Suggestion;

/**
 * Endpoints: SuggestionEndpoint
 */
class SuggestionEndpoint extends EndpointAbstract
{
    /**
     * @var string Endpoint
     */
    protected $resourceEndpoint = "suggestions";
    /**
     * @var \Sidn\Suggestion\Api\Resources\Suggestion References the recource of the Suggestion endpoint
     */
    protected $resourceClass = Suggestion::class;

    /**
     * Initiate instance of endpoint object
     *
     * @return \Sidn\Suggestion\Api\Resources\Suggestion
     */
    public function initiate()
    {
        return $this->getResourceObject();
    }

    /**
     * Get the object that is used by this API endpoint.
     *
     * @return \Sidn\Suggestion\Api\Resources\Suggestion
     */
    protected function getResourceObject()
    {
        return new $this->resourceClass($this->client);
    }
}

<?PHP

namespace Sidn\Suggestion\Api\Resources;

use Sidn\Suggestion\Api\SidnSuggestionApiClient;

/**
 * Resources: BaseResource
 */
abstract class BaseResource
{
    /**
     * @var \Sidn\Suggestion\Api\SidnSuggestionApiClient Client
     */
    protected $client;

    /**
     * BaseResource
     * 
     * @param \Sidn\Suggestion\Api\SidnSuggestionApiClient $client 
     * @return void
     */
    public function __construct(SidnSuggestionApiClient $client)
    {
        $this->client = $client;
    }
}

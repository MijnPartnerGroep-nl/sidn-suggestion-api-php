<?PHP

namespace Sidn\Suggestion\Api\Resources;

use Sidn\Suggestion\Api\SidnSuggestionApiClient;


abstract class BaseResource
{
    /**
     * @var \Sidn\Suggestion\Api\SidnSuggestionApiClient
     */
    protected $client;

    /**
     * @param \Sidn\Suggestion\Api\SidnSuggestionApiClient $client
     * @return void
     */
    public function __construct(SidnSuggestionApiClient $client)
    {
        $this->client = $client;
    }
}

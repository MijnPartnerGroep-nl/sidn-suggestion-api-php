<?PHP
namespace Sidn\Suggestion\Api;


class SidnSuggestionApiClient
{
    
    const API_ENDPOINT = "https://suggestions.api.sidn.nl/";

    const HTTP_GET = "GET";
    const HTTP_POST = "POST";

    const TIMEOUT = 10;

    const CONNECT_TIMEOUT = 2;

    protected $httpClient;

    protected $apiEndpoint = SELF::API_ENDPOINT;

    public $oauth;

    public $suggestions;

    protected $lastHttpResponseStatusCode;


    public function __construct(ClientInterface $httpClient = null) {
        $this->httpClient = $httpClient;

        if(!$this->httpClient) {
            $this->httpClient = new Client([
                GuzzleRequestOptions::VERIFY => CaBundle::getBundledCaBundlePath(),
                GuzzleRequestOptions::TIMEOUT => self::TIMEOUT,
                GuzzleRequestOptions::CONNECT_TIMEOUT => self::CONNECT_TIMEOUT
            ]);
        }

    }
}
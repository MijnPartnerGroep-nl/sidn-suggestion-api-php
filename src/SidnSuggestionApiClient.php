<?PHP

namespace Sidn\Suggestion\Api;

use Composer\CaBundle\CaBundle;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions as GuzzleRequestOptions;
use Sidn\Suggestion\Api\Exceptions\ApiException;
use Sidn\Suggestion\Api\Endpoints\AuthenticateEndpoint;
use Sidn\Suggestion\Api\Endpoints\SuggestionEndpoint;

/**
 * Client: SidnSuggestionApiClient
 */
class SidnSuggestionApiClient
{
    /**
     * Endpoint of SIDN's Suggestion API
     */
    private const API_ENDPOINT = "https://suggestions.api.sidn.nl/";

    /**
     * Default Timeout
     */
    private const TIMEOUT = 10;
    private const CONNECT_TIMEOUT = 2;

    /**
     * @var \GuzzleHttp\ClientInterface Guzzle's ClientInterface
     */
    protected $httpClient;

    /**
     * @var string endPoint
     */
    protected $apiEndpoint = self::API_ENDPOINT;

    /**
     * @var string accessToken
     */
    protected $accessToken;

     /**
     * RESTful Auhtentication endpoint.
     *
     * @var \Sidn\Suggestion\Api\Endpoints\AuthenticateEndpoint
     */
    public $ep_authenticate;
    /**
     * RESTful Suggestion endpoint.
     *
     * @var \Sidn\Suggestion\Api\Endpoints\SuggestionEndpoint
     */
    public $ep_suggestion;

    /**
     * RESTful Authentication resource.
     *
     * @var \Sidn\Suggestion\Api\Resources\Authenticate
     */
    public $authenticate;
     /**
     * RESTful Suggestion resource.
     *
     * @var \Sidn\Suggestion\Api\Resources\Suggestion
     */
    public $suggestion;

    /**
     * SidnSuggestionApiClient
     *
     * @param \GuzzleHttp\ClientInterface|null $httpClient Optionally define your own
     * httpClient for testing purposes mostly
     * @throws \Sidn\Suggestion\Api\Exceptions\ApiException
     */
    public function __construct(ClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient;

        if (!$this->httpClient) {
            $this->httpClient = new Client([
                GuzzleRequestOptions::VERIFY => CaBundle::getBundledCaBundlePath(),
                GuzzleRequestOptions::TIMEOUT => self::TIMEOUT,
                GuzzleRequestOptions::CONNECT_TIMEOUT => self::CONNECT_TIMEOUT
            ]);
        }

        $this->ep_authenticate = new AuthenticateEndpoint($this);
        $this->ep_suggestion = new SuggestionEndpoint($this);
        $this->authenticate = $this->ep_authenticate->initiate();
        $this->suggestion = $this->ep_suggestion->initiate();
    }

    /**
     * Send and HTTP Request. This method is used by the resource specific classes.
     *
     * @param string $httpMethod
     * @param string $url
     * @param string|null $httpBody
     * @param array|null $httpHeaders
     *
     * @return \stdClass|null
     * @throws \Sidn\Suggestion\Api\Exceptions\ApiException
     */
    public function sendHttpRequest($httpMethod, $url, $httpBody = null, array $httpHeaders = null)
    {
        $url = $this->apiEndpoint . $url;

        $userAgent = "MijnPartnerGroep-nl/SidnSuggestionApiClient";

        $headers = [
            'Accept' => $httpMethod == "application/json",
            'User-Agent' => $userAgent,
        ];
        if (is_array($httpHeaders) && count($httpHeaders) > 0) {
            $headers = array_merge($headers, $httpHeaders);
        }
        if ($this->accessToken != null) {
            $headers["Authorization"] = "Bearer " . $this->accessToken;
        }

        $request = new Request($httpMethod, $url, $headers, $httpBody);

        try {
            $response = $this->httpClient->send($request, ['http_errors' => false]);
        } catch (GuzzleException $e) {
            throw ApiException::createFromGuzzleException($e, $request);
        }


        if (!$response) {
            throw new ApiException("No API response received.", 0, null, $request);
        } else {
            if ($response->getStatusCode() == 401) {
                throw ApiException::createFromString(
                    "Authentication failed. Check your access token",
                    $response,
                    $request
                );
            }
            if ($response->getStatusCode() >= 400) {
                throw ApiException::createFromResponse($response, $request);
            }

            $result = (string)$response->getBody();

            $object = json_decode($result, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new ApiException("Incorrect json response: '{$result}'.");
            }

            return $object;
        }
    }

    /**
     * Use this method to set the Access Token. Reuse the access token as long as it is
     * not expired. Reauthenticate after expiration.
     *
     * @param string $accessToken OAuth access token, in JWT format
     *
     * @return \Sidn\Suggestion\Api\SidnSuggestionApiClient
     */
    public function setAccessToken($accessToken)
    {
        $accessToken = trim($accessToken);
        $this->accessToken = $accessToken;
        return $this;
    }
}

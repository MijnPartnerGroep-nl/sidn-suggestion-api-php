<?PHP

namespace Sidn\Suggestion\Api\Exceptions;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Resources: ApiException
 */
class ApiException extends \Exception
{

    /**
     * @var \Psr\Http\Message\RequestInterface|null Request that threw the exception
     */
    protected $request;

    /**
     * @var \Psr\Http\Message\ResponseInterface|null Response that threw the exception
     */
    protected $response;

    /**
     * ApiException
     *
     * @param string $message
     * @param int $code
     * @param string|null $path
     * @param \Psr\Http\Message\RequestInterface|null $request
     * @param \Psr\Http\Message\ResponseInterface|null $response
     * @param \Throwable|null $previous
     * @throws \Sidn\Suggestion\Api\Exceptions\ApiException
     */
    public function __construct(
        $message = "",
        $code = 0,
        $path = null,
        RequestInterface $request = null,
        ResponseInterface $response = null,
        $previous = null
    ) {
        $timestamp = date("Y-m-dTH:i:s");

        if (!empty($response)) {
            $this->response = $response;

            $object = static::parseResponseBody($this->response);
            if (isset($object->timestamp)) {
                $timestamp = $object->timestamp;
            }
        }
        $message = "[{$timestamp}] " . $message;

        if (!is_null($path)) {
            $message .= "\r\n" . $path;
        }

        $this->request = $request;
        if ($request) {
            $requestBody = $request->getBody()->__toString();

            if ($requestBody) {
                $message .= ". Request body: {$requestBody}";
            }
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * Create Exception based on GuzzleException for initialization issues
     *
     * @param \GuzzleHttp\Exception\GuzzleException $guzzleException
     * @param \Psr\Http\Message\RequestInterface|null $request
     * @param \Throwable|null $previous
     * @return \Sidn\Suggestion\Api\Exceptions\ApiException
     * @throws \Sidn\Suggestion\Api\Exceptions\ApiException
     */
    public static function createFromGuzzleException(
        \GuzzleHttp\Exception\GuzzleException $guzzleException,
        $request = null,
        $previous = null
    ) {
        if (method_exists($guzzleException, 'hasResponse') && method_exists($guzzleException, 'getResponse')) {
            if ($guzzleException->hasResponse()) {
                return static::createFromResponse($guzzleException->getResponse(), $request, $previous);
            }
        }

        return new self($guzzleException->getMessage(), $guzzleException->getCode(), null, $request, null, $previous);
    }

    /**
     * Create Exception based on response payload
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Psr\Http\Message\RequestInterface|null $request
     * @param \Throwable|null $previous
     * @return \Sidn\Suggestion\Api\Exceptions\ApiException
     * @throws \Sidn\Suggestion\Api\Exceptions\ApiException
     */
    public static function createFromResponse(
        ResponseInterface $response,
        RequestInterface $request = null,
        $previous = null
    ) {
        $object = static::parseResponseBody($response);

        return new self(
            "Error executing API call ({$object->timestamp}: {$object->error}): {$object->message}",
            $object->status,
            $object->path,
            $request,
            $response,
            $previous
        );
    }

    /**
     * Create Exception mesage from string, for edge cases
     *
     * @param string $message
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Psr\Http\Message\RequestInterface|null $request
     * @param \Throwable|null $previous
     * @return \Sidn\Suggestion\Api\Exceptions\ApiException
     * @throws \Sidn\Suggestion\Api\Exceptions\ApiException
     */
    public static function createFromString(
        string $message,
        ResponseInterface $response,
        RequestInterface $request = null,
        $previous = null
    ) {

        return new self(
            "Error executing API call: {$message}",
            $response->getStatusCode(),
            "",
            $request,
            null,
            $previous
        );
    }

     /**
     * Parse repsonse payload
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return \stdClass|null
     * @throws \Sidn\Suggestion\Api\Exceptions\ApiException
     */
    protected static function parseResponseBody(
        $response
    ) {
        $body = (string) $response->getBody();

        $object = json_decode($body);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new self("Unable to decode response: '{$body}'.");
        }

        return $object;
    }
}

<?PHP

namespace Sidn\Suggestion\Api\Resources;

class ResourceFactory {
    /**
     * Create resource object from Api result
     *
     * @param object \Sidn\Suggestion\Api\Resources\Authenticate|\Sidn\Suggestion\Api\Resources\Suggestion
     * @param BaseResource $resource
     *
     * @return \Sidn\Suggestion\Api\Resources\Authenticate|\Sidn\Suggestion\Api\Resources\Suggestion
     */
    public static function resourceFromResult($result, BaseResource $resource)
    {
        foreach ($result as $property => $value) {
            if(in_array($property, $resource->expectedProperties)) {
                $resource->{$property} = $value;
            }
        }

        return $resource;
    }
}
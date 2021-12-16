<?php

namespace App\Utilities\SCIO;

use App\Enums\PIIStatus;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PIIChecker
{
    /**
     * The base URI of the service.
     */
    protected $baseURI;

    /**
     * The timeout of the http requests.
     */
    protected $requestTimeout;

    /**
     * The token to use for making requests to the service.
     */
    protected $token;

    /**
     * The presigned urls ttl.
     */
    protected $presignedTtl;

    public function __construct()
    {
        $generator = new TokenGenerator();
        $this->token = $generator->getToken();
        $this->baseURI = env('SCIO_SERVICES_BASE_API_URL');
        $this->requestTimeout = env('REQUEST_TIMEOUT_SECONDS', 15);
        $this->presignedTtl = env('PRESIGNED_URL_TTL_IN_SECONDS', 86400);
    }

    /**
     * Performs a check for a file.
     *
     * @param string $filepath
     * @param object $user
     * @return void
     */
    public function check($path, $user)
    {
        if (empty($path)) {
            throw new Exception('The file path is required.');
        }

        if (empty($user) || empty($user->email)) {
            throw new Exception('The user email is required.');
        }

        $email = $user->email;
        $presignedUrl = Storage::temporaryUrl($path, now()->addSeconds($this->presignedTtl));

        $response = Http::timeout($this->requestTimeout)
            ->retry(3, 500)
            ->acceptJson()
            ->asJson()
            ->withToken($this->token)
            ->post("$this->baseURI/pii/submitjob", [
                "email" => $email,
                "path" => $presignedUrl,
                "title" => $path,
                "mode" => "recall",
                "language" => "eng"
            ]);

        $response->throw();

        return $response->json();
    }

    /**
     * Retrieves the report for a given file identifier.
     *
     * @param string $filepath
     * @param object $user
     * @return void
     */
    public function getReport($id)
    {
        if (empty($id)) {
            throw new Exception('The PII file identifier is required.');
        }

        $response = Http::timeout($this->requestTimeout)
            ->retry(3, 500)
            ->acceptJson()
            ->asJson()
            ->withToken($this->token)
            ->get("$this->baseURI/pii/getcompletedjobsbyid/$id");

        $response->throw();

        $persons = data_get($response->json(), '0.report.detection.0.namedentity');
        $geocoordinates = data_get($response->json(), '0.report.detection.0.geocoordinates');

        return ['persons' => $persons, 'geocoordinates' => $geocoordinates];
    }

    /**
     * Retrieves the status for a given file identifier.
     *
     * @param string $filepath
     * @param object $user
     * @return void
     */
    public function getStatus($id): string
    {
        if (empty($id)) {
            throw new Exception('The PII file identifier is required.');
        }

        $response = Http::timeout($this->requestTimeout)
            ->retry(3, 500)
            ->acceptJson()
            ->asJson()
            ->withToken($this->token)
            ->get("$this->baseURI/pii/getcompletedjobsbyid/$id");

        $response->throw();

        $persons = data_get($response->json(), '0.report.detection.0.namedentity');
        $geocoordinates = data_get($response->json(), '0.report.detection.0.geocoordinates');

        if (count($persons) > 0 || count($geocoordinates) > 0) {
            return PIIStatus::FAILED;
        }

        return PIIStatus::PASSED;
    }
}

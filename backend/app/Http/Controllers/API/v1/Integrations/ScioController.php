<?php

namespace App\Http\Controllers\API\v1\Integrations;

use App\Http\Controllers\Controller;
use App\Http\Requests\SCiO\Projects\ListProjectsRequest;
use App\Http\Requests\SCiO\Languages\ListLanguagesRequest;
use App\Http\Requests\SCiO\Mimetypes\GetMimetypeRequest;
use App\Http\Requests\SCiO\Vocabularies\ListVocabulariesRequest;
use App\Http\Requests\SCiO\Vocabularies\AutocompleteTermRequest;
use App\Http\Requests\SCiO\Vocabularies\ExtractTermsRequest;
use App\Utilities\SCIO\TokenGenerator;
use Cache;
use Http;
use Log;

class ScioController extends Controller
{

    protected $token;
    protected $cacheTtl;
    protected $baseURI;
    protected $requestTimeout;

    public function __construct()
    {
        $generator = new TokenGenerator();
        $this->token = $generator->getToken();
        $this->cacheTtl = env('CACHE_TTL_SECONDS');
        $this->baseURI = env('SCIO_SERVICES_BASE_API_URL');
        $this->requestTimeout = env('REQUEST_TIMEOUT_SECONDS', 10);
    }

    public function listLanguages(ListLanguagesRequest $request)
    {
        $cacheKey = 'scio_languages';
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $response = Http::timeout($this->requestTimeout)
            ->acceptJson()
            ->asJson()
            ->withToken($this->token)
            ->get("$this->baseURI/languages/languagelist");

        $json = $response->json('languages');

        if ($response->ok()) {
            Cache::put($cacheKey, $json, $this->cacheTtl);
        }

        return response()->json($json, $response->status());
    }

    public function getMimetype(GetMimetypeRequest $request)
    {
        $response = Http::timeout($this->requestTimeout)
            ->acceptJson()
            ->asJson()
            ->withToken($this->token)
            ->post("$this->baseURI/vocabularies/resolvemimetypes", $request->all());

        return response()->json($response->json('response'), $response->status());
    }

    public function listVocabularies(ListVocabulariesRequest $request)
    {
        $cacheKey = 'scio_vocabularies';
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $response = Http::timeout($this->requestTimeout)
            ->acceptJson()
            ->asJson()
            ->withToken($this->token)
            ->post("$this->baseURI/vocabularies/getvocabularies", [
                'language' => 'eng',
                'types_enabled' => ['extracted']
            ]);

        $json = $response->json('response');

        if ($response->ok()) {
            Cache::put($cacheKey, $json, $this->cacheTtl);
        }

        return response()->json($json, $response->status());
    }

    public function autocompleteTerm(AutocompleteTermRequest $request)
    {
        $response = Http::timeout($this->requestTimeout)
            ->acceptJson()
            ->asJson()
            ->withToken($this->token)
            ->post("$this->baseURI/vocabularies/getautocomplete", [
                'autocomplete' => $request->term,
                'alias' => $request->index,
                'field' => 'ngram_tokenizer',
            ]);

        $json = $response->json('response.suggestions');

        return response()->json($json, $response->status());
    }

    public function extractTerms(ExtractTermsRequest $request)
    {
        $response = Http::timeout($this->requestTimeout)
            ->acceptJson()
            ->asJson()
            ->withToken($this->token)
            ->post("$this->baseURI/vocabularies/extractterms", [
                'artifact' => $request->text,
            ]);

        return response()->json($response->json('data'), $response->status());
    }

    public function listProjects(ListProjectsRequest $request)
    {
        $cacheKey = 'scio_projects';
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $response = Http::timeout($this->requestTimeout)
            ->acceptJson()
            ->asJson()
            ->withToken($this->token)
            ->get("$this->baseURI/projects/resolveprojects");

        $json = $response->json('response.data');

        if ($response->ok()) {
            Cache::put($cacheKey, $json, $this->cacheTtl);
        }

        return response()->json($json, $response->status());
    }
}

<?php

namespace App\Http\Controllers\API\v1\OAuth;

use App\Enums\IdentityProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Http;
use Ramsey\Uuid\Rfc4122\UuidV4;

class GlobusController extends Controller
{

    /**
     * The ORCID base URI. Can be used to change from sandbox mode to production and vice-versa.
     */
    protected $baseURI;

    /**
     * The provided ORCID client ID.
     */
    protected $clientID;

    /**
     * The provided ORCID client secret.
     */
    protected $clientSecret;

    /**
     * The ORCID redirect URI where the authorization code will be sent.
     */
    protected $redirectURI;

    /**
     * The ORCID grant type.
     */
    protected $grantType;

    /**
     * Where to redirect after successful login.
     */
    protected $redirectTo;

    /**
     * A random value that is returned to the client in the authorization response.
     */
    protected $state;

    /**
     * The scope that we are requesting access for.
     */
    protected $scope;

    /**
     * The scope of the requested access.
     */

    public function __construct()
    {
        $this->baseURI = env('GLOBUS_BASE_URI');
        $this->clientID = env('GLOBUS_CLIENT_ID');
        $this->clientSecret = env('GLOBUS_CLIENT_SECRET');
        $this->redirectURI = env('GLOBUS_REDIRECT_URI');
        $this->grantType = 'authorization_code';
        $this->redirectTo = env('SCRIBE_LOGIN_URL');
        $this->state = (string) UuidV4::uuid4();
        $this->scope = env('GLOBUS_SCOPE');
    }

    public function redirect()
    {
        $this->scope = urlencode($this->scope);
        $to = "$this->baseURI/authorize?client_id=$this->clientID&response_type=code&scope=/authenticate&redirect_uri=$this->redirectURI&response_type=code&scope=$this->scope";

        return redirect($to);
    }

    public function callback(Request $request)
    {
        $authorizationCode = $request->code;

        $response = Http::timeout(5)
            ->withBasicAuth($this->clientID, $this->clientSecret)
            ->asForm()
            ->acceptJson()
            ->post(
                "$this->baseURI/token",
                [
                    'grant_type' => $this->grantType,
                    'code' => $authorizationCode,
                    'redirect_uri' => $this->redirectURI,
                ]
            );

        if ($response->failed()) {
            return response()->json(['errors' => [
                'error' => 'Authenticating with Globus failed.'
            ]], 400);
        }

        // The response as an array from JSON.
        $json = $response->json();

        // IDP details.
        $idp = IdentityProvider::GLOBUS;
        $idpId = $json['id_token'];

        // TODO: Finish the implementation.
        return;

        // Check for a user.
        $user = User::where('identity_provider', $idp)
            ->where('identity_provider_external_id', $idpId)
            ->first();

        if (!$user) {
            // $name = explode(" ", $json['name']);
            // $firstname = isset($name[0]) ? $name[0] : '';
            // $lastname = isset($name[1]) ? $name[1] : '';
            $user = User::create([
                // 'firstname' => $firstname,
                // 'lastname' => $lastname,
                'identity_provider' => $idp,
                'identity_provider_external_id' => $idpId,
            ]);
        }

        // Get a valid access token for the user.
        $token = auth('api')->login($user);

        // The redirect address that contains the url query string param "access_token".
        $to = $this->redirectTo . '?' . http_build_query(['access_token' => $token]);

        return redirect($to);
    }
}

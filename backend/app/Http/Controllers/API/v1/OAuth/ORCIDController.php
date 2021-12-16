<?php

namespace App\Http\Controllers\API\v1\OAuth;

use App\Enums\IdentityProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Http;

class ORCIDController extends Controller
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


    public function __construct()
    {
        $this->baseURI = env('ORCID_BASE_URI');
        $this->clientID = env('ORCID_CLIENT_ID');
        $this->clientSecret = env('ORCID_CLIENT_SECRET');
        $this->redirectURI = env('ORCID_REDIRECT_URI');
        $this->grantType = 'authorization_code';
        $this->redirectTo = env('SCRIBE_LOGIN_URL');
    }

    public function redirect()
    {
        $to = "$this->baseURI/authorize?client_id=$this->clientID&response_type=code&scope=/authenticate&redirect_uri=$this->redirectURI";

        return redirect($to);
    }

    public function callback(Request $request)
    {
        $authorizationCode = $request->code;

        $response = Http::timeout(5)->asForm()->acceptJson()->post(
            "$this->baseURI/token",
            [
                'client_id' => $this->clientID,
                'client_secret' => $this->clientSecret,
                'grant_type' => $this->grantType,
                'code' => $authorizationCode,
                'redirect_uri' => $this->redirectURI,
            ]
        );

        if ($response->failed()) {
            return response()->json(['errors' => [
                'error' => 'Authenticating with ORCID failed.'
            ]], 400);
        }

        // The response as an array from JSON.
        $json = $response->json();

        // IDP details.
        $idp = IdentityProvider::ORCID;
        $idpId = $json['orcid'];

        // Check for a user.
        $user = User::where('identity_provider', $idp)
            ->where('identity_provider_external_id', $idpId)
            ->first();

        if (!$user) {
            $name = explode(" ", $json['name']);
            $firstname = isset($name[0]) ? $name[0] : '';
            $lastname = isset($name[1]) ? $name[1] : '';
            $user = User::create([
                'firstname' => $firstname,
                'lastname' => $lastname,
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

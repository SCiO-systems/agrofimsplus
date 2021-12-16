<?php

namespace App\Http\Controllers\API\v1\Integrations;

use App\Http\Controllers\Controller;
use App\Utilities\DOI\DOIParser;
use App\Utilities\DOI\DOIValidator;
use Exception;
use Illuminate\Http\Request;

class DoiController extends Controller
{
    public function checkDoi(Request $request)
    {
        $doi = $request->doi;
        $title = $request->title;

        $parser = new DOIParser();
        $validator = new DOIValidator();

        try {
            $parsedDoi = $parser->parse($doi);
            $doiUrl = "https://doi.org/$parsedDoi";
            $result = $validator->validate($parsedDoi, $title);
            $result['value'] = $parsedDoi;
            $result['url'] = $doiUrl;
        } catch (Exception $ex) {
            return response()->json(['errors' => ['error' => $ex->getMessage()]], 400);
        }

        return response()->json($result, 200);
    }
}

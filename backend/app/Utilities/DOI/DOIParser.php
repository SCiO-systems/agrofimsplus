<?php

namespace App\Utilities\DOI;

use Exception;

class DOIParser
{
    public function parse($doi = '')
    {
        if (empty($doi)) {
            throw new Exception('An empty DOI was provided.');
        }

        if (!str_contains($doi, "10.") || !str_contains($doi, "/")) {
            throw new Exception('An invalid DOI was provided.');
        }

        if (str_contains($doi, 'doi.org')) {
            $doiPart = explode("doi.org/", $doi);
            return end($doiPart);
        }

        return $doi;
    }
}

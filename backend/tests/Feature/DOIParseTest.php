<?php

namespace Tests\Feature;

use App\Utilities\DOI\DOIParser;
use Exception;
use Tests\TestCase;

class DOIParseTest extends TestCase
{
    /**
     * @test
     */
    public function a_valid_doi_can_be_parsed()
    {
        $doi = "10.1016/j.biocon.2020.108849";

        $parser = new DOIParser();

        $this->assertEquals($doi, $parser->parse($doi));
    }

    /**
     * @test
     */
    public function a_doi_from_url_can_be_parsed()
    {
        $doi = "10.1016/j.biocon.2020.108849";
        $doiUrlOrg = "https://doi.org/10.1016/j.biocon.2020.108849";
        $doiUrlDx = "https://dx.doi.org/10.1016/j.biocon.2020.108849";

        $parser = new DOIParser();

        $this->assertEquals($doi, $parser->parse($doiUrlOrg));
        $this->assertEquals($doi, $parser->parse($doiUrlDx));
    }

    /**
     * @test
     */
    public function an_empty_doi_cannot_be_parsed()
    {
        $doi = "";

        $parser = new DOIParser();

        $this->expectException(Exception::class);
        $this->assertEquals($doi, $parser->parse($doi));
    }

    /**
     * @test
     */
    public function an_invalid_doi_cannot_be_parsed()
    {
        $doi = "1234";

        $parser = new DOIParser();

        $this->expectException(Exception::class);
        $this->assertEquals($doi, $parser->parse($doi));
    }
}

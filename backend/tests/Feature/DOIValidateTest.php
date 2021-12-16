<?php

namespace Tests\Feature;

use App\Utilities\DOI\DOIValidator;
use Tests\TestCase;

class DOIValidateTest extends TestCase
{
    /**
     * @test
     */
    public function a_crossref_doi_can_be_validated_with_title()
    {
        $doi = "10.1016/j.biocon.2020.108849";
        $title = "Taking the pulse of Earth's tropical forests using networks of highly distributed plots";

        $validator = new DOIValidator();
        $data = $validator->validate($doi, $title);

        $this->assertEquals([
            'provider' => DOIValidator::PROVIDER_CROSSREF,
            'verified' => true,
            'matchesTitle' => true
        ], $data);
    }

    /**
     * @test
     */
    public function a_crossref_doi_can_be_validated_without_title()
    {
        $doi = "10.1016/j.biocon.2020.108849";
        $title = "...";

        $validator = new DOIValidator();
        $data = $validator->validate($doi, $title);

        $this->assertEquals([
            'provider' => DOIValidator::PROVIDER_CROSSREF,
            'verified' => true,
            'matchesTitle' => false
        ], $data);
    }

    /**
     * @test
     */
    public function a_datacite_doi_can_be_validated_with_title()
    {
        $doi = "10.7910/dvn/26496";
        $title = "Yemen Social Accounting Matrix, 2012";

        $validator = new DOIValidator();
        $data = $validator->validate($doi, $title);

        $this->assertEquals([
            'provider' => DOIValidator::PROVIDER_DATACITE,
            'verified' => true,
            'matchesTitle' => true
        ], $data);
    }

    /**
     * @test
     */
    public function a_datacite_doi_can_be_validated_without_title()
    {
        $doi = "10.7910/dvn/26496";
        $title = "...";

        $validator = new DOIValidator();
        $data = $validator->validate($doi, $title);

        $this->assertEquals([
            'provider' => DOIValidator::PROVIDER_DATACITE,
            'verified' => true,
            'matchesTitle' => false
        ], $data);
    }

    /**
     * @test
     */
    public function a_doi_without_a_title_can_be_validated()
    {
        $doi = "10.7910/dvn/26496";
        $title = "";

        $validator = new DOIValidator();
        $data = $validator->validate($doi, $title);

        $this->assertEquals([
            'provider' => DOIValidator::PROVIDER_DATACITE,
            'verified' => true,
            'matchesTitle' => false
        ], $data);
    }
}

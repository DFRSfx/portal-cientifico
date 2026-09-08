<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Book;
use App\Models\Jury;
use App\Models\User;
use \GuzzleHttp\Pool;
use App\Models\Author;
use App\Models\Output;
use App\Models\Keyword;
use App\Models\Project;
use App\Models\Language;
use App\Models\OutputType;
use App\Models\Supervisor;
use PhpParser\JsonDecoder;
use App\Models\BookChapter;
use App\Models\ServiceType;
use App\Models\Supervision;
use App\Models\AuthorDegree;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\TransferStats;
use App\Models\JournalArticle;
use App\Models\ConferencePaper;
use App\Models\ConferencePoster;
use App\Models\MagazineArticles;
use App\Models\NewspaperArticle;
use App\Models\AuthorEmployments;
use App\Models\AuthorCitationName;
use App\Models\CommiteeMembership;
use App\Models\ConferenceAbstract;
use App\Models\EventParticipation;
use App\Models\ThesisDissertation;
use Illuminate\Support\Facades\DB;
use App\Models\EventAdministration;
use App\Models\AuthorSpokenLanguage;
use Illuminate\Support\Facades\Http;
use App\Models\DomainActivitiesTopic;
use Illuminate\Support\Facades\Config;
use App\Models\JournalReviewingRefeering;
use App\Models\ConferenceReviewingRefeering;
use App\Http\Controllers\StringTreatmentController;

class CienciaVitaeController extends Controller
{
    private $apiAddress;
    private $endPoint;
    private $client;
    private $authorization;

    // This array is used to decide the types of outputs that the algoritm has to consider while inserting data
    private $outputs = [
        "P122" => [
            "table-class" => ConferencePaper::class,
            "title-atribute-name" => "paper-title",
            "year-column" => "conference-date",
            "attribute" => "conference-paper"
        ],
        "P101" => [
            "table-class" => JournalArticle::class,
            "title-atribute-name" => "article-title",
            "year-column" => "publication-date",
            "attribute" => "journal-article"
        ],
        "P113" => [
            "table-class" => MagazineArticles::class,
            "title-atribute-name" => "article-title",
            "year-column" => "publication-date",
            "attribute" => "magazine-article"
        ],
        "P124" => [
            "table-class" => ConferencePoster::class,
            "title-atribute-name" => "title",
            "year-column" => "conference-date",
            "attribute" => "conference-poster"
        ],
        "P103" => [
            "table-class" => Book::class,
            "title-atribute-name" => "title",
            "year-column" => "publication-year",
            "attribute" => "book"
        ],
        "P105" => [
            "table-class" => BookChapter::class,
            "title-atribute-name" => "chapter-title",
            "year-column" => "publication-year",
            "attribute" => "book-chapter"
        ],
        "P108" => [
            "table-class" => ThesisDissertation::class,
            "title-atribute-name" => "title",
            "year-column" => "completion-date",
            "attribute" => "dissertation"
        ],
        "P110" => [
            "table-class" => NewspaperArticle::class,
            "title-atribute-name" => "article-title",
            "year-column" => "publication-date",
            "attribute" => "newspapper-article"
        ],
        "P123" => [
            "table-class" => ConferenceAbstract::class,
            "title-atribute-name" => "article-title",
            "year-column" => "publication-date",
            "attribute" => "conference-abstract"
        ]
    ];

    // This array has all the services attributes that are going to dictacte witch table should be inserted
    private $cienciaVitaeServiceAttrs = [
        "S201" => [
            "table-class" => EventAdministration::class,
            "attribute" => "event-administration",
            "start-date-attribute" => "activity-start-date",
            "end-date-attribute" => "activity-end-date",
        ],
        "S202" => [
            "table-class" => EventParticipation::class,
            "attribute" => "event-participation",
            "start-date-attribute" => "start-date",
            "end-date-attribute" => "end-date",
        ],
        "S206" => [
            "table-class" => CommiteeMembership::class,
            "attribute" => "committee-membership",
            "start-date-attribute" => "start-date",
            "end-date-attribute" => "end-date",
        ],
        "S103" => [
            "table-class" => JournalReviewingRefeering::class,
            "attribute" => "journal-reviewing-refereeing",
            "start-date-attribute" => "start-date",
            "end-date-attribute" => "end-date",
        ],
        "S104" => [
            "table-class" => ConferenceReviewingRefeering::class,
            "attribute" => "conference-reviewing-refereeing",
            "start-date-attribute" => "start-date",
            "end-date-attribute" => "end-date",
        ],
        "S105" => [
            "table-class" => Jury::class,
            "attribute" => "graduate-examination",
            "start-date-attribute" => "start-date",
            "end-date-attribute" => "start-date",
        ],
        "S110" => [
            "table-class" => Supervision::class,
            "attribute" => "research-based-degree-supervision",
            "start-date-attribute" => "start-date",
            "end-date-attribute" => "end-date",
        ],

    ];

    public function __construct()
    {
        $this->authorization = config("app.ciencia_vitae_pd");

        $this->apiAddress = config("app.ciencia_vitae_url_pd");

        $curlSSLVerify = config('app.curl_ssl_verify');

        $this->client = new \GuzzleHttp\Client(['base_uri' => $this->apiAddress, 'verify' => $curlSSLVerify, "headers" => ["accept" => "application/json", "authorization" => $this->authorization]]);
    }

    /**
     * Makes one request to the ciencia Vitae Api
     * @param string $endPoint
     *  @param string $query
     * @param $type
     * @return array|int returns the code of the response in case of error or returns the response array
     */
    public function cienciaVitaeRequest($endPoint, $query = "?lang=EN", $type = "GET")
    {
        try {
            $url = $endPoint . $query;

            $request = $this->client->request('GET', $url);

            $responseArray = json_decode($request->getBody()->getContents(), true);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return $e->getCode();
        }

        return $responseArray;
    }

    /**
     * Checks if the author curriculum is public
     * @param string
     * @return int 1|0|-1
     * 0 - Private Profile | 1 - public profile | -1 - 403 or 404 or 401 error code
     */
    public function authorCurriculumIsPub($cienciaVitaeId)
    {
        $response = 0;

        $apiResponse = $this->cienciaVitaeRequest("api-user/" . urlencode($cienciaVitaeId) . "/access-privileges");

        if (gettype($apiResponse) != "array") {
            $response = -1;
        } else if ($apiResponse["api-user"]["privacy-level"]["value"] == "Public") {
            $response = 1;
        }

        return $response;
    }

    /**
     * Updates The author information profile
     * @param array $responseArray
     * @param object Author Model
     * @return array $response
     */
public function updateAuthorInformation($responseArray, $authorObject)
{
    $response = ["error" => false, "message" => ""];

    try {
        $orcid = "";
        $googleScholarId = "";
        $researcherId = "";
        $scopusId = "";

        if (isset($responseArray["identifying-info"]["author-identifiers"]["author-identifier"])) {
            foreach ($responseArray["identifying-info"]["author-identifiers"]["author-identifier"] as $identifier) {
                    $identifierType = $identifier["identifier-type"] ?? [];
                    $identifierTypeCodeRaw =
                        ($identifierType["code"] ?? null) ??
                        ($identifierType["value"] ?? null) ??
                        ($identifierType["label"] ?? null) ??
                        ($identifierType["name"] ?? null) ??
                        ($identifier["identifier-type-code"] ?? null) ??
                        "";
                    $identifierTypeCode = strtoupper(trim((string) $identifierTypeCodeRaw));

                    $identifierValueRaw =
                        ($identifier["identifier"] ?? null) ??
                        ($identifier["value"] ?? null) ??
                        ((is_array($identifier["identifier"] ?? null) ? ($identifier["identifier"]["value"] ?? null) : null)) ??
                        "";
                    $identifierValue = trim((string) $identifierValueRaw);

                    if ($identifierTypeCode === "" || $identifierValue === "") {
                        continue;
                    }

                    // Normalize by substring checks because Ciencia Vitae may introduce new identifier type codes.
                    if (str_contains($identifierTypeCode, 'ORCID')) {
                        $orcid = $identifierValue;
                    } elseif (str_contains($identifierTypeCode, 'SCOPUS')) {
                        $scopusId = $identifierValue;
                    } elseif (str_contains($identifierTypeCode, 'GOOGLE')) {
                        $googleScholarId = $identifierValue;
                    } elseif (str_contains($identifierTypeCode, 'LATTES')) {
                        $lattesId = $identifierValue;
                    } elseif (str_contains($identifierTypeCode, 'AUTHENTICUS')) {
                        // Alguns utilizadores colocam aqui valores do tipo "Web of Science Researcher IDAIC: 8607-2022".
                        // Guardamos só o identificador quando conseguimos extraí-lo.
                        $normalizedAuthenticus = $identifierValue;
                        if (preg_match('/([A-Z]-\d{4}-\d{4}|\d{4}-\d{4})/i', $identifierValue, $m)) {
                            $normalizedAuthenticus = $m[1];
                        }
                        $authenticusId = $normalizedAuthenticus;
                    } elseif (str_contains($identifierTypeCode, 'RESEARCHGATE')) {
                        $researchGateProfile = $identifierValue;
                    } elseif (
                        str_contains($identifierTypeCode, 'RESEARCHER') ||
                        str_contains($identifierTypeCode, 'WEB_OF_SCIENCE') ||
                        str_contains($identifierTypeCode, 'WOS') ||
                        str_contains($identifierTypeCode, 'AIC')
                    ) {
                        // Web of Science / ResearcherID / AIC variants
                        $researcherId = $identifierValue;
                    }
                }
            }

            // Em alguns perfis o "Researcher Id" pode vir como um URL (ex.: ResearchGate).
            // Para manter compatibilidade com dados já importados, se não houver `id_researcher` usamos o `researchgate_profile`.
            if ($researcherId === '' && $researchGateProfile !== '') {
                $researcherId = $researchGateProfile;
            }

        $authorObject->update([
                "orcid" => $orcid,
                "id_google_scholar" => $googleScholarId,
                "id_researcher" => $researcherId,
                "id_scopus_author" =>  $scopusId,
                "id_authenticus" => $authenticusId,
                "researchgate_profile" => $researchGateProfile,
                "id_lattes" => $lattesId,
                "profile_is_public" => 1,
                "resume" => ($responseArray["identifying-info"]["resume"]["value"] ?? null),
                "profile_updated_date" => date('Y-m-d', strtotime($responseArray["last-modified-date"])),
                "profile_image_is_public" => ($responseArray["identifying-info"]["person-info"]["photography"]["privacy-level"] == "publico")
            ]);

        if (isset($responseArray["identifying-info"]["citation-names"])) {
            $citations = [];

            foreach ($responseArray["identifying-info"]["citation-names"]["citation-name"] as $citationName) {
                $citation = AuthorCitationName::firstOrCreate(["citation_name" => $citationName["value"], "author_id" => $authorObject->id]);
                array_push($citations, $citation->id);
            }

            $outputsToDelete = $authorObject->output()
                ->whereHas("citations", function ($query) use ($citations) {
                    $query->whereNotIn("author_citation_names.id", $citations);
                })
                ->pluck('outputs.id');

            if ($outputsToDelete->isNotEmpty()) {
                Output::whereIn('id', $outputsToDelete)->delete();
            }

            $authorObject->citationName()->whereNotIn("id", $citations)->delete();
        }

        if (isset($responseArray["identifying-info"]["emails"])) {
            $authorObject->emails()->delete();

            $emailsToInsert = [];

            foreach ($responseArray["identifying-info"]["emails"]["email"] as $emails) {
                if (isset($emails["emailAddress"])) {
                    array_push($emailsToInsert, [
                        "email" => ($emails["emailAddress"])
                    ]);
                }
            }

            if (count($emailsToInsert) != 0) {
                $authorObject->emails()->createMany($emailsToInsert);
            }
        }

        if (isset($responseArray["identifying-info"]["phone-numbers"])) {
            $authorObject->phones()->delete();

            $phonesToInsert = [];

            foreach ($responseArray["identifying-info"]["phone-numbers"]["phone-number"] as $phone) {
                if (isset($phone["localNumber"])) {
                    array_push($phonesToInsert, [
                        "phone" => $phone["localNumber"]
                    ]);
                }
            }

            if (count($phonesToInsert) != 0) {
                $authorObject->phones()->createMany($phonesToInsert);
            }
        }

        if (isset($responseArray["identifying-info"]["mailing-addresses"])) {
            $authorObject->addresses()->delete();

            $addressesToInsert = [];

            foreach ($responseArray["identifying-info"]["mailing-addresses"]["mailing-address"] as $address) {
                if (isset($address["streetAddress"])) {
                    array_push($addressesToInsert, [
                        "address" => $address["streetAddress"]
                    ]);
                }
            }

            if (count($addressesToInsert) != 0) {
                $authorObject->addresses()->createMany($addressesToInsert);
            }
        }

        if (isset($responseArray["identifying-info"]["language-competencies"])) {
            $languagesToInsert = [];

            foreach ($responseArray["identifying-info"]["language-competencies"]["language-competency"] as $languages) {
                $insertedOrExistinglanguage = Language::firstOrCreate(["language" => $languages["language"]["value"]]);

                $languagesToInsert[$insertedOrExistinglanguage->id] = [
                    "speech_level" => (($languages["speak"]["value"] ?? null)),
                    "writing_level" => (($languages["write"]["value"] ?? null)),
                    "listening_level" => (($languages["understand-spoken"]["value"] ?? null)),
                    "peer_review_level" => (($languages["peer-review"]["value"] ?? null)),
                    "read-level" => (($languages["read"]["value"] ?? null))
                ];
            }

            $authorObject->languages()->sync($languagesToInsert);
        }

        if (isset($responseArray["identifying-info"]["domains-activity"])) {
            $domainActivities = [];

            foreach ($responseArray["identifying-info"]["domains-activity"]["domain-activity"] as $domainActivity) {
                if (isset($domainActivity["topic"])) {
                    $treatedActivities = StringTreatmentController::explodeAndReplaceSpecificCharacters($domainActivity["topic"]);

                    foreach ($treatedActivities as $treatedActivity) {
                        $insertedOrExistingDomain = DomainActivitiesTopic::firstOrCreate(["topic_name" => $treatedActivity]);
                        array_push($domainActivities, $insertedOrExistingDomain->id);
                    }
                }
            }

            if (count($domainActivities) != 0) {
                $authorObject->activity()->sync($domainActivities);
            }
        }

        if (isset($responseArray["fundings"])) {
            $authorObject->project()->delete();

            $projects = [];

            foreach ($responseArray["fundings"]["funding"] as $funding) {
                array_push($projects, [
                    "funding_category" => $this->returnValueIfNotNull($funding, "funding-category", "value"),
                    "project_title" => $this->returnValueIfNotNull($funding, "project-title"),
                    "project_description" => $this->returnValueIfNotNull($funding, "project-description"),
                    "start_date_year" => $this->returnValueIfNotNull($funding, "start-date", "year"),
                    "start_date_month" => $this->returnValueIfNotNull($funding, "start-date", "month"),
                    "start_date_day" => $this->returnValueIfNotNull($funding, "start-date", "day"),
                    "end_date_year" => $this->returnValueIfNotNull($funding, "end-date", "year"),
                    "end_date_month" => $this->returnValueIfNotNull($funding, "end-date", "month"),
                    "end_date_day" => $this->returnValueIfNotNull($funding, "end-date", "day"),
                    "start_participation_year" => $this->returnValueIfNotNull($funding, "start-date-participation", "year"),
                    "start_participation_month" => $this->returnValueIfNotNull($funding, "start-date-participation", "month"),
                    "start_participation_day" => $this->returnValueIfNotNull($funding, "start-date-participation", "day"),
                    "end_participation_year" => $this->returnValueIfNotNull($funding, "end-date-participation", "year"),
                    "end_participation_month" => $this->returnValueIfNotNull($funding, "end-date-participation", "month"),
                    "end_participation_day" => $this->returnValueIfNotNull($funding, "end-date-participation", "day"),
                    "status" => $this->returnValueIfNotNull($funding, "status", "value"),
                    "investigation_role" => $this->returnValueIfNotNull($funding, "investigation-role", "value"),
                    "investigation_role_description" => $this->returnValueIfNotNull($funding, "investigation-role-description", "value"),
                    "total_amount" => $this->returnValueIfNotNull($funding, "totalAmount"),
                    "fundingCategory" => $this->returnValueIfNotNull($funding, "funding-category", "value"),
                    "program_name" => $this->returnValueIfNotNull($funding, "program-name"),
                    "year_awarded" => $this->returnValueIfNotNull($funding, "year-awarded"),
                    "competitive" => $this->returnValueIfNotNull($funding, "competitive"),
                    "funding_renewable" => $this->returnValueIfNotNull($funding, "funding-renewable"),
                    "author_id" => $authorObject->id
                ]);
            }

            if (count($projects) != 0) {
                $authorObject->project()->createMany($projects);
            }
        }

        if (isset($responseArray["employments"]["employment"])) {
            $authorObject->employments()->delete();

            $employments = [];

            foreach ($responseArray["employments"]["employment"] as $employment) {
                array_push($employments, [
                    "employment_category" => $this->returnValueIfNotNull($employment, "employment-category", "value"),
                    "institution_name" => $this->returnValueIfNotNull($employment, "institution", "institution-name"),
                    "position_type" => $this->returnValueIfNotNull($employment, "position-type", "value"),
                    "position_title" => $this->returnValueIfNotNull($employment, "position-title", "value"),
                    "position_title_group" => $this->returnValueIfNotNull($employment, "position-title-group", "value"),
                    "start_date_year" => $this->returnValueIfNotNull($employment, "start-date", "year"),
                    "start_date_month" => $this->returnValueIfNotNull($employment, "start-date", "month"),
                    "start_date_day" => $this->returnValueIfNotNull($employment, "start-date", "day"),
                    "end_date_year" => $this->returnValueIfNotNull($employment, "end-date", "year"),
                    "end_date_month" => $this->returnValueIfNotNull($employment, "end-date", "month"),
                    "end_date_day" => $this->returnValueIfNotNull($employment, "end-date", "day"),
                    "author_id" => $authorObject->id
                ]);
            }

            if (count($employments) != 0) {
                $authorObject->employments()->createMany($employments);
            }
        }

        if (isset($responseArray["degrees"]["degree"])) {
            $authorObject->degrees()->delete();

            foreach ($responseArray["degrees"]["degree"] as $degree) {
                $institution = ($degree["institutions"]["institution"][0] ?? null);
                $researchClassification = ($degree["research-classifications"]["research-classification"][0] ?? null);

                $degreeInserted = AuthorDegree::create([
                    "degree_type" => $this->returnValueIfNotNull($degree, "degree-type", "value"),
                    "degree_name" => $this->returnValueIfNotNull($degree, "degree-name"),
                    "institution_name" => $this->returnValueIfNotNull($institution, "institution-name"),
                    "degree_major" => $this->returnValueIfNotNull($degree, "degree-major"),
                    "description" => $this->returnValueIfNotNull($degree, "description"),
                    "classification" => $this->returnValueIfNotNull($degree, "classification"),
                    "degree_status" => $this->returnValueIfNotNull($degree, "degree-status", "value"),
                    "research_classification" => $this->returnValueIfNotNull($researchClassification, "value"),
                    "thesis_title" => $this->returnValueIfNotNull($degree, "thesis", "thesis-title"),
                    "start_date_year" => $this->returnValueIfNotNull($degree, "start-date", "year"),
                    "start_date_month" => $this->returnValueIfNotNull($degree, "start-date", "month"),
                    "start_date_day" => $this->returnValueIfNotNull($degree, "start-date", "day"),
                    "end_date_year" => $this->returnValueIfNotNull($degree, "end-date", "year"),
                    "end_date_month" => $this->returnValueIfNotNull($degree, "end-date", "month"),
                    "end_date_day" => $this->returnValueIfNotNull($degree, "end-date", "day"),
                    "author_id" => $authorObject->id
                ]);

                if (isset($degree["thesis"]["supervisors"]["supervisor"])) {
                    foreach ($degree["thesis"]["supervisors"]["supervisor"] as $supervisor) {
                        if (null !== $this->returnValueIfNotNull($supervisor, "supervisor-name")) {
                            Supervisor::create([
                                "supervisor_name" => $this->returnValueIfNotNull($supervisor, "supervisor-name"),
                                "supervisor_role" => $this->returnValueIfNotNull($supervisor, "supervisor-role", "value"),
                                "ciencia_vitae" => $this->returnValueIfNotNull($supervisor, "ciencia-id"),
                                "degree_id" => $degreeInserted->id
                            ]);
                        }
                    }
                }
            }
        }

        if (isset($responseArray["services"])) {
            $authorObject->service()->delete();
            $this->removePolymorphicRelations($this->cienciaVitaeServiceAttrs, "service");

            foreach ($responseArray["services"]["service"] as $service) {
                $servicesAttributes = ($this->cienciaVitaeServiceAttrs[$service["service-category"]["code"]] ?? null);

                if ($servicesAttributes) {
                    $serviceInformation = $service[$servicesAttributes["attribute"]];
                    $serviceType = ServiceType::firstOrCreate(["name" => $service["service-category"]["value"]]);
                    $newPolymorphicService = new $servicesAttributes["table-class"]($this->serviceData($servicesAttributes["attribute"], $serviceInformation));
                    $newPolymorphicService->save();

                    $newService = $newPolymorphicService->service()->create([
                        "start_year" => $this->returnValueIfNotNull($serviceInformation, $servicesAttributes["start-date-attribute"], "year"),
                        "start_month" => $this->returnValueIfNotNull($serviceInformation, $servicesAttributes["start-date-attribute"], "month"),
                        "start_day" => $this->returnValueIfNotNull($serviceInformation, $servicesAttributes["start-date-attribute"], "day"),
                        "end_year" => $this->returnValueIfNotNull($serviceInformation, $servicesAttributes["end-date-attribute"], "year"),
                        "end_month" => $this->returnValueIfNotNull($serviceInformation, $servicesAttributes["end-date-attribute"], "month"),
                        "end_day" => $this->returnValueIfNotNull($serviceInformation, $servicesAttributes["end-date-attribute"], "day"),
                        "type_id" => $serviceType->id,
                        "author_id" => $authorObject->id
                    ]);

                    $this->insertKeywords($newService, $serviceInformation);
                }
            }
        }

        if (isset($responseArray["outputs"])) {
            $publicationsAdded = [];
            
            \Log::info('Starting outputs processing', [
                'total_outputs' => count($responseArray["outputs"]["output"])
            ]);
            
            foreach ($responseArray["outputs"]["output"] as $output) {
                $publicationsAttributes = ($this->outputs[$output["output-type"]["code"]]) ?? null;

                \Log::info('Processing output', [
                    'output_id' => $output["id"],
                    'type_code' => $output["output-type"]["code"],
                    'has_attributes' => !is_null($publicationsAttributes)
                ]);

                if ($publicationsAttributes) {
                    $publicationTypeAttribute = $publicationsAttributes["attribute"];
                    $publicationTitleAttribute = $publicationsAttributes["title-atribute-name"];
                    $publicationInformation = $output[$publicationTypeAttribute];
                    $doi = "";

                    if (isset($publicationInformation["identifiers"])) {
                        foreach ($publicationInformation["identifiers"]["identifier"] as $identifier) {
                            if ($identifier["identifier-type"]["code"] == "doi") {
                                $doi = $identifier["identifier"];
                                break;
                            }
                        }
                    }

                    if (isset($publicationInformation["authors"])) {
                        \Log::info('Processing authors', [
                            'author_count' => count($publicationInformation["authors"]["author"])
                        ]);
                        
                        foreach ($publicationInformation["authors"]["author"] as $authorData) {
                            $citationName = trim($authorData["value"] ?? "");
                            
                            if (!$citationName) {
                                \Log::warning('No citation name found for author', ['author_data' => $authorData]);
                                continue;
                            }

                            $citation = AuthorCitationName::where('author_id', $authorObject->id)
                                ->whereRaw('LOWER(TRIM(citation_name)) = ?', [strtolower($citationName)])
                                ->first();

                            if (!$citation) {
                                \Log::info('Creating new citation name', [
                                    'citation_name' => $citationName,
                                    'author_id' => $authorObject->id
                                ]);
                                
                                $citation = AuthorCitationName::create([
                                    'citation_name' => $citationName,
                                    'author_id' => $authorObject->id
                                ]);
                            }

                            \Log::info('Citation lookup', [
                                'citation_name_used' => $citationName,
                                'citation_id' => $citation->id
                            ]);

                            $existingPublication = $authorObject->output()
                                ->where("ciencia_vitae_pub_id", "=", $output["id"])
                                ->first();

                            $title = ($publicationInformation[$publicationTitleAttribute]) ?? "";
                            if (strlen($title) > 255) {
                                $title = substr($title, 0, 255);
                            }

                            \Log::info('Publication processing', [
                                'output_id' => $output["id"],
                                'exists' => !is_null($existingPublication),
                                'title' => $title
                            ]);

                            if ($existingPublication) {
                                $publication = $existingPublication;

                                $dataToUpdate = $this->getPublicationData($publicationTypeAttribute, $publicationInformation);
                                $tableColumns = \Schema::getColumnListing($publication->polymorphic()->getRelated()->getTable());
                                
                                foreach ($dataToUpdate as $key => $value) {
                                    if (is_string($value) && strlen($value) > 255) {
                                        $dataToUpdate[$key] = substr($value, 0, 255);
                                    }
                                }
                                
                                $dataToUpdate = array_filter(
                                    $dataToUpdate,
                                    fn($key) => in_array($key, $tableColumns),
                                    ARRAY_FILTER_USE_KEY
                                );

                                $publication->polymorphic()->update($dataToUpdate);

                                $publication->update([
                                    "title" => $title,
                                    "doi" => ($doi) ?? null,
                                    "citation_string" => $this->returnValueIfNotNull($publicationInformation, "authors", "citation"),
                                    "year" => $this->returnValueIfNotNull($publicationInformation, $publicationsAttributes["year-column"], "year")
                                ]);
                                
                                \Log::info('Updated existing publication', ['id' => $publication->id]);
                            } else {
                                $publicationType = OutputType::firstOrCreate(["name" => $output["output-type"]["value"]]);
                                
                                $publicationData = $this->getPublicationData($publicationTypeAttribute, $publicationInformation);
                                
                                foreach ($publicationData as $key => $value) {
                                    if (is_string($value) && strlen($value) > 255) {
                                        $publicationData[$key] = substr($value, 0, 255);
                                    }
                                }
                                
                                $newPolymorphicPublication = new $publicationsAttributes["table-class"]($publicationData);
                                $newPolymorphicPublication->save();

                                $publication = $newPolymorphicPublication->output()->create([
                                    "title" => $title,
                                    "doi" => ($doi) ?? null,
                                    "type_id" => $publicationType->id,
                                    "citation_string" => $this->returnValueIfNotNull($publicationInformation, "authors", "citation"),
                                    "year" => $this->returnValueIfNotNull($publicationInformation, $publicationsAttributes["year-column"], "year"),
                                    "ciencia_vitae_pub_id" => $output["id"]
                                ]);
                                
                                \Log::info('Created new publication', ['id' => $publication->id]);
                            }

                            array_push($publicationsAdded, $output["id"]);

                            $publication->citations()->syncWithoutDetaching([
                                $citation->id => ["author_id" => $authorObject->id]
                            ]);
                            
                            \Log::info('Synced citation', [
                                'publication_id' => $publication->id,
                                'citation_id' => $citation->id
                            ]);

                            $this->insertKeywords($publication, $publicationInformation);

                            break;
                        }
                    }
                }
            }

            \Log::info('Publications added', [
                'count' => count($publicationsAdded),
                'ids' => $publicationsAdded
            ]);

            if (count($publicationsAdded) > 0) {
                $outputIdsToDelete = $authorObject->output()
                    ->whereNotIn("ciencia_vitae_pub_id", $publicationsAdded)
                    ->pluck('outputs.id');

                if ($outputIdsToDelete->isNotEmpty()) {
                    Output::whereIn('id', $outputIdsToDelete)->delete();
                }

                $this->removePolymorphicRelations($this->outputs, "output");
            }
        }

        $response["message"] = "Profile updated successfully";
        
    } catch (\Exception $e) {
        $response["message"] = $e->getMessage();
        $response["error"] = true;
        
        \Log::error('Update Author Information Error', [
            'author_id' => $authorObject->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }

    return $response;
}
    /**
     * The outputType expetected is the atribute name of the type that is going to be inserted
     * This atribute tells witch data is going to be inserted in the model
     * @param string $outputType
     * @param array $data
     * @return array
     */
    public function getPublicationData($outputType, $data)
    {

        try {

            switch ($outputType) {
                case "conference-paper":
                    $array = [
                        "conference_name" => ($data["conference-name"]) ?? null,
                        "presentation_year" => ($data["presentation-date"]["year"]) ?? null,
                        "presentation_month" => ($data["presentation-date"]["month"]) ?? null,
                        "presentation_day" => ($data["presentation-date"]["day"]) ?? null,
                        "conference_year" => ($data["conference-date"]["year"]) ?? null,
                        "conference_month" => ($data["conference-date"]["month"]) ?? null,
                        "conference_day" => ($data["conference-date"]["day"]) ?? null,
                        "conf_country" => (($data["conference-location"]["country"]["value"]) ?? null),
                        "conf_city" => (($data["conference-location"]["city"]) ?? null),
                        "proceedings_title" => ($data["proceedings-title"]) ?? null,
                        "start_page" => hexdec(($data["page-range-from"]) ?? null),
                        "end_page" => hexdec(($data["page-range-to"]) ?? null),
                        "status" => ($data["publication-status"]["value"]) ?? null,
                        "pub_country" => (($data["publication-location"]["country"]["value"]) ?? null),
                        "pub_city" => (($data["publication-location"]["city"]) ?? null),
                        "publisher" => ($data["proceedings-publisher"]) ?? null,
                        "role" => ($data["authoring-role"]["value"]) ?? null
                    ];
                    break;
                case "journal-article":
                    $array = [
                        "journal" => ($data["journal"] ?? null),
                        "volume" => ($data["volume"] ?? null),
                        "issue" => ($data["issue"] ?? null),
                        "start_page" => substr(hexdec(($data["page-range-from"] ?? null)), 0, 5),
                        "end_page" =>  substr(hexdec(($data["page-range-to"] ?? null)), 0, 5),
                        'city' => (($data["publication-location"]["city"]) ?? null),
                        'publisher' => "",
                        'open_access' => ($data["open-access"] ?? null),
                        'status' => ($data["publication-status"]["value"] ?? null),
                        "publication_year" => ($data["publication-date"]["year"]) ?? null,
                        "publication_month" => ($data["publication-date"]["month"]) ?? null,
                        "publication_day" => ($data["publication-date"]["day"]) ?? null,
                        'country' => (($data["publication-location"]["country"]["value"]) ?? null),
                        'role' => ($data["authoring-role"]["value"] ?? null),
                        'url' => ($data["url"] ?? null),
                        "refreed" => ($data["refereed"] ?? null)
                    ];
                    break;
                case "newspapper-article":
                    $array = [
                        "article_title" => ($data["article-title"] ?? null),
                        "newspaper" => ($data["newspaper"] ?? null),
                        "section" => ($data["section"] ?? null),
                        "volume" => hexdec(($data["volume"] ?? null)),
                        "edition" => ($data["edition"] ?? null),
                        "page_range_from" => hexdec(($data["page-range-from"] ?? null)),
                        "page_range_to" => hexdec(($data["page-range-to"] ?? null)),
                        "publication_date" => ($data["publication-date"]["year"] ?? null),
                        "publication_location" => ($data["publication-location"]["country"]["value"] ?? null),
                        "url" => ($data["url"] ?? null),
                        "research_classifications" => ($data["research-classifications"] ?? null),
                    ];
                    break;
                case "conference-abstract":
                    $array = [
                        "article_title" => $data["article-title"] ?? null,
                        // "section" => $data["section"] ?? null,
                        "volume" => $data["volume"] ?? null,
                        // "edition" => $data["edition"] ?? null,
                        "page_range_from" => hexdec($data["page-range-from"] ?? null),
                        "page_range_to" => hexdec($data["page-range-to"] ?? null),
                        "publication_date" => ($data["publication-date"]["year"] ?? null),
                        // "publication_location" => ($data["publication-location"]["country"]["value"] ?? null),
                    ];
                    break;
                case "magazine-article":
                    $array = [
                        "magazine" => ($data["magazine"]) ?? null,
                        'role' => ($data["authoring-role"]["value"] ?? null),
                        'url' => ($data["url"] ?? null),
                        "issue" => ($data["issue"] ?? null),
                        "start_page" => ($data["page-range-from"] ?? null),
                        "end_page" => ($data["page-range-to"] ?? null),
                        "volume" => ($data["volume"] ?? null),
                        "publication_year" => ($data["publication-date"]["year"]) ?? null,
                        "publication_month" => ($data["publication-date"]["month"]) ?? null,
                        "publication_day" => ($data["publication-date"]["day"]) ?? null,
                        "pub_country" => (($data["publication-location"]["country"]["value"]) ?? null),
                        "pub_city" => (($data["publication-location"]["city"]) ?? null),
                    ];
                    break;
                case "dissertation":
                    $array = [
                        "title" => $this->returnValueIfNotNull($data, "title"),
                        "volumes_number" => $this->returnValueIfNotNull($data, "number-of-volumes"),
                        "degree_type" => $this->returnValueIfNotNull($data, "degreeType", "value"),
                        "classification" => $this->returnValueIfNotNull($data, "classification"),
                        "completionDate" => $this->returnValueIfNotNull($data, "completionDate"),
                        "url" => $this->returnValueIfNotNull($data, "completionDate"),
                        "supervisors" => null,
                        "researchClassifications" => null,
                        "keywords" => null

                    ];
                    break;
                case "book-chapter":
                    $array = [
                        "book_title" => ($data["book-title"]) ?? null,
                        "book_volume" => ($data["book-volume"]) ?? null,
                        "book_edition" => ($data["book-edition"]) ?? null,
                        "chapter_start_page" => ($data["chapter-page-range-from"] ?? null),
                        "chapter_end_page" => ($data["chapter-page-range-to"] ?? null),
                        "refreed" => ($data["refereed"] ?? null),
                        "publisher" => ($data["book-publisher"]) ?? null,
                        "status" => ($data["publication-status"]["value"]) ?? null,
                        "publication_year" => ($data["publication-year"]) ?? null,
                        "publication_month" => ($data["publication-date"]["month"]) ?? null,
                        "publication_day" => ($data["publication-date"]["day"]) ?? null,
                        'role' => ($data["authoring-role"]["value"]) ?? null,
                        'url' => ($data["url"]) ?? null,
                    ];
                    break;
                case "book":
                    $array = [
                        "volume" => ($data["volume"] ?? null),
                        "status" => ($data["publication-status"]["value"]) ?? null,
                        "pages_number" => ($data["number-of-pages"] ?? null),
                        'url' => ($data["url"] ?? null),
                        "pub_country" => (($data["publication-location"]["country"]["value"]) ?? null),
                        "pub_city" => (($data["publication-location"]["city"]) ?? null),
                        "edition" => ($data["edition"] ?? null),
                        "refreed" => ($data["refereed"] ?? null),
                        "publisher" => ($data["publisher"] ?? null),
                        'role' => ($data["authoring-role"]["value"] ?? null),
                        "publication_year" => ($data["publication-year"]) ?? null,
                    ];
                    break;
                case "conference-poster":
                    $array = [
                        "conference_name" => ($data["conference-name"]) ?? null,
                        'role' => ($data["authoring-role"]["value"] ?? null),
                        "conference_year" => ($data["conference-date"]["year"]) ?? null,
                        "conference_month" => ($data["conference-date"]["month"]) ?? null,
                        "conference_day" => ($data["conference-date"]["day"]) ?? null
                    ];
                    break;
            } 


            return $array;
        } catch (\Exception $e) {

            dd($e);
        }
    }

    public function serviceData($serviceType, $data)
    {

        switch ($serviceType) {
            case "event-administration":
                $array = [
                    "activity_start_year" => $this->returnValueIfNotNull($data, "activity-start-date", "year"),
                    "activity_start_month" => $this->returnValueIfNotNull($data, "activity-start-date", "month"),
                    "activity_start_day" => $this->returnValueIfNotNull($data, "activity-start-date", "day"),
                    "activity_end_year" => $this->returnValueIfNotNull($data, "activity-end-date", "year"),
                    "activity_end_month" => $this->returnValueIfNotNull($data, "activity-end-date", "month"),
                    "activity_end_day" => $this->returnValueIfNotNull($data, "activity-end-date", "day"),
                    "event_description" => $this->returnValueIfNotNull($data, "event-description"),
                    "event_type" => $this->returnValueIfNotNull($data, "event-type", "value"),
                    "administrative_role" => $this->returnValueIfNotNull($data, "administrative-role", "value")
                ];
                break;
            case "event-participation":
                $array = [
                    "event_description" => $this->returnValueIfNotNull($data, "event-description"),
                    "event_name" => $this->returnValueIfNotNull($data, "event-name"),
                    "event_type" => $this->returnValueIfNotNull($data, "event-type", "value"),
                    "start_date_year" => $this->returnValueIfNotNull($data, "startDate", "year"),
                    "start_date_month" => $this->returnValueIfNotNull($data, "startDate", "month"),
                    "start_date_day" => $this->returnValueIfNotNull($data, "startDate", "day"),
                    "end_date_year" => $this->returnValueIfNotNull($data, "endDate", "year"),
                    "end_date_month" => $this->returnValueIfNotNull($data, "endDate", "month"),
                    "end_date_day" => $this->returnValueIfNotNull($data, "endDate", "day"),
                ];
                break;
            case "committee-membership":
                $array = [
                    "committee_name" => $this->returnValueIfNotNull($data, "committee-name"),
                    "membership_type" => $this->returnValueIfNotNull($data, "membership-type", "value"),
                ];
                break;
            case "journal-reviewing-refereeing":
                $array = [
                    "journal" => $this->returnValueIfNotNull($data, "journal", "value"),
                    "press" => $this->returnValueIfNotNull($data, "press"),
                    "works_reviewed" =>  $this->returnValueIfNotNull($data, "works-reviewed"),
                    "url" => $this->returnValueIfNotNull($data, "url"),
                ];
                break;
            case "conference-reviewing-refereeing":
                $array = [
                    "conference" => $this->returnValueIfNotNull($data, "conference"),
                    "conference_host" => $this->returnValueIfNotNull($data, "conference-host"),
                    "works_reviewed" => $this->returnValueIfNotNull($data, "works-reviewed")
                ];
                break;
            case "research-based-degree-supervision":
                // dd($this->returnValueIfNotNull($data, "thesis-title"));
                $array = [
                    "thesis_title" => $this->returnValueIfNotNull($data, "thesis-title"),
                    "supervisory_title" => $data['supervisory-type']['value'],
                    "start_date" => $data['start-date']['year'] ?? '',
                    "end_date" => $data['end-date']['year'] ?? '',
                ];
                break;
            case "graduate-examination":
                $array = [
                    "theme" => $this->returnValueIfNotNull($data, "theme"),
                    "examination_subject" => $data['examination-subject'],
                    "start_date" => $data['date']['year'] ?? '',
                    "end_date" => $data['date']['year'] ?? '',
                    "year" => $data['date']['year'] ?? '',

                ];
                break;
        }

        return $array;
    }

    public function removeAllPolymorphicRelations()
    {
        // removes all services
        $this->removePolymorphicRelations($this->cienciaVitaeServiceAttrs, "service");

        // removes all publications
        $this->removePolymorphicRelations($this->outputs, "output");
    }

    /**
     *
     * Private Methods
     *
     */

    private function insertKeywords($model, $data)
    {
        // Adds or Updates the outputKeyword
        if (isset($data["keywords"])) {
            $keywords = [];

            foreach ($data["keywords"]["keyword"] as $keyword) {
                $treatedKeyWords = StringTreatmentController::explodeAndReplaceSpecificCharacters($keyword);

                foreach ($treatedKeyWords as $treatedKeyWord) {
                    $keyWordToAssociate = Keyword::firstOrCreate(["keyword" => $treatedKeyWord]);

                    array_push($keywords, $keyWordToAssociate->id);
                }
            }

            $model->keywords()->sync($keywords);
        }
    }

    private function removePolymorphicRelations($array, $relationName)
    {
        foreach ($array as $element) {
            $element["table-class"]::doesntHave($relationName)->delete();
        }
    }

    private function returnValueIfNotNull($array, $key1, $key2 = null)
    {
        $value = (isset($key2)) ? ($array[$key1][$key2]) ?? null : ($array[$key1]) ?? null;

        return $value;
    }
}

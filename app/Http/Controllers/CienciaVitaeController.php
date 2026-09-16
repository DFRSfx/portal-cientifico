<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Book;
use App\Models\Jury;
use App\Models\User;
use \GuzzleHttp\Pool;
use GuzzleHttp\Exception\GuzzleException;
use App\Models\Author;
use App\Models\Output;
use App\Models\Keyword;
use App\Models\Project;
use App\Models\Language;
use App\Models\OutputType;
use App\Models\Supervisor;
use PhpParser\JsonDecoder;
use App\Models\BookChapter;
use App\Models\BookReview;
use App\Models\EditedBook;
use App\Models\EncyclopediaEntry;
use App\Models\ExhibitionCatalogue;
use App\Models\JournalIssue;
use App\Models\ServiceType;
use App\Models\Supervision;
use App\Models\AuthorDegree;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\TransferStats;
use App\Models\JournalArticle;
use App\Models\Manual;
use App\Models\ConferencePaper;
use App\Models\ConferencePoster;
use App\Models\MagazineArticles;
use App\Models\NewsletterArticle;
use App\Models\NewspaperArticle;
use App\Models\OnlineResource;
use App\Models\Preprint;
use App\Models\PrefacePostface;
use App\Models\Report;
use App\Models\AuthorEmployments;
use App\Models\AuthorCitationName;
use App\Models\CommiteeMembership;
use App\Models\ConferenceAbstract;
use App\Models\EventParticipation;
use App\Models\Test;
use App\Models\ThesisDissertation;
use App\Models\Translation;
use App\Models\Website;
use App\Models\WorkingPaper;
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
        "P102" => [
            "table-class" => JournalIssue::class,
            "title-atribute-name" => "issue-title",
            "year-column" => "publication-date",
            "attribute" => "journal-issue"
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
        "P104" => [
            "table-class" => EditedBook::class,
            "title-atribute-name" => "title",
            "year-column" => "publication-year",
            "attribute" => "edited-book"
        ],
        "P105" => [
            "table-class" => BookChapter::class,
            "title-atribute-name" => "chapter-title",
            "year-column" => "publication-year",
            "attribute" => "book-chapter"
        ],
        "P106" => [
            "table-class" => BookReview::class,
            "title-atribute-name" => "review-title",
            "year-column" => "date-of-review-publication",
            "attribute" => "book-review"
        ],
        "P107" => [
            "table-class" => Translation::class,
            "title-atribute-name" => "title",
            "year-column" => "publication-year",
            "attribute" => "translation"
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
        "P111" => [
            "table-class" => NewsletterArticle::class,
            "title-atribute-name" => "article-title",
            "year-column" => "publication-date",
            "attribute" => "newsletter-article"
        ],
        "P112" => [
            "table-class" => EncyclopediaEntry::class,
            "title-atribute-name" => "entry-title",
            "year-column" => "publication-date",
            "attribute" => "encyclopedia-entry"
        ],
        "P114" => [
            "table-class" => DictionaryEntry::class,
            "title-atribute-name" => "dictionary-title",
            "year-column" => "publication-date",
            "attribute" => "dictionary-entry"
        ],
        "P115" => [
            "table-class" => Report::class,
            "title-atribute-name" => "report-title",
            "year-column" => "date-submitted",
            "attribute" => "report"
        ],
        "P116" => [
            "table-class" => WorkingPaper::class,
            "title-atribute-name" => "title",
            "year-column" => "publication-date",
            "attribute" => "working-paper"
        ],
        "P118" => [
            "table-class" => Manual::class,
            "title-atribute-name" => "title",
            "year-column" => "publication-year",
            "attribute" => "manual"
        ],
        "P119" => [
            "table-class" => OnlineResource::class,
            "title-atribute-name" => "title",
            "year-column" => "creation-date",
            "attribute" => "online-resource"
        ],
        "P120" => [
            "table-class" => Test::class,
            "title-atribute-name" => "title",
            "year-column" => "date-first-used",
            "attribute" => "test"
        ],
        "P121" => [
            "table-class" => Website::class,
            "title-atribute-name" => "title",
            "year-column" => "launch-date",
            "attribute" => "website"
        ],
        "P123" => [
            "table-class" => ConferenceAbstract::class,
            "title-atribute-name" => "article-title",
            "year-column" => "publication-date",
            "attribute" => "conference-abstract"
        ],
        "P125" => [
            "table-class" => ExhibitionCatalogue::class,
            "title-atribute-name" => "title",
            "year-column" => "publication-year",
            "attribute" => "exhibition-catalogue"
        ],
        "P126" => [
            "table-class" => PrefacePostface::class,
            "title-atribute-name" => "preface-postface-title",
            "year-column" => "publication-year",
            "attribute" => "preface-postface"
        ],
        "P127" => [
            "table-class" => Preprint::class,
            "title-atribute-name" => "title",
            "year-column" => "date-submitted",
            "attribute" => "preprint"
        ]
    ];

    // This array has all the services attributes that are going to dictacte witch table should be inserted
    private $cienciaVitaeServiceAttrs = [
        "S201" => [
            "table-class" => EventAdministration::class,
            "attribute" => "event-administration",
            "start-date-attribute" => "activity-start-date",
            "end-date-attribute" => "activity-end-date",
            "title-atribute-name" => "article-title",

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
        $timeoutSeconds = 30;
        $connectTimeoutSeconds = 10;

        $this->client = new \GuzzleHttp\Client([
            'base_uri' => $this->apiAddress,
            'verify' => $curlSSLVerify,
            'timeout' => $timeoutSeconds,
            'connect_timeout' => $connectTimeoutSeconds,
            'headers' => [
                'accept' => 'application/json',
                'authorization' => $this->authorization,
            ],
        ]);
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
        } catch (GuzzleException $e) {
            $code = $e->getCode();
            return $code ? $code : -1;
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

        if (!is_array($apiResponse)) {
            return -1;
        }

        $effectivePrivacy = strtolower(data_get($apiResponse, 'privilege.effective-privacy-level.value', ''));
        $effectiveCode = strtolower(data_get($apiResponse, 'privilege.effective-privacy-level.code', ''));
        $roleCode = strtoupper(data_get($apiResponse, 'privilege.effective-role.code', ''));
        $roleValue = strtolower(data_get($apiResponse, 'privilege.effective-role.value', ''));
        $apiUserPrivacy = strtolower(data_get($apiResponse, 'api-user.privacy-level.value', ''));

        if (
            in_array($roleCode, ['R', 'W']) ||
            in_array($roleValue, ['read', 'write']) ||
            in_array($effectivePrivacy, ['public', 'semi public', 'semi-public']) ||
            in_array($effectiveCode, ['publico', 'semi-publico']) ||
            in_array($apiUserPrivacy, ['public', 'semi public', 'semi-public'])
        ) {
            $response = 1;
        }

        return $response;
    }

    /**
     * Download official PDF of the curriculum from Ciência Vitae
     * @param string $cienciaVitaeId
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function getCurriculumPdfResponse($cienciaVitaeId)
    {
        try {
            $url = "curriculum/" . urlencode($cienciaVitaeId) . "/pdf";
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'accept' => 'application/pdf',
                    'authorization' => $this->authorization,
                ],
                'http_errors' => true,
            ]);

            return $response;
        } catch (\Exception $e) {
            \Log::error("Error downloading CV PDF from CienciaVitae: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Fetch co-authors for a specific output from Ciência Vitae
     * @param string $cienciaVitaeId
     * @param string|int $outputId
     * @return array|null
     */
    public function getOutputCoauthors($cienciaVitaeId, $outputId)
    {
        try {
            $url = "curriculum/" . urlencode($cienciaVitaeId) . "/output/" . urlencode($outputId) . "/coauthors";
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'accept' => 'application/json',
                    'authorization' => $this->authorization,
                ],
                'http_errors' => true,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            \Log::warning("Error fetching output coauthors from CienciaVitae: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Fetch distinctions/awards from Ciência Vitae
     * @param string $cienciaVitaeId
     * @return array|null
     */
    public function getDistinctions($cienciaVitaeId)
    {
        try {
            $url = "curriculum/" . urlencode($cienciaVitaeId) . "/distinction";
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'accept' => 'application/json',
                    'authorization' => $this->authorization,
                ],
                'http_errors' => true,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            \Log::warning("Error fetching distinctions from CienciaVitae: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Fetch curriculum groups (e.g. fundingsOutputs links) from Ciência Vitae
     * @param string $cienciaVitaeId
     * @return array|null
     */
    public function getCurriculumGroups($cienciaVitaeId)
    {
        try {
            $url = "curriculum/" . urlencode($cienciaVitaeId) . "/groups";
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'accept' => 'application/json',
                    'authorization' => $this->authorization,
                ],
                'http_errors' => true,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            \Log::warning("Error fetching curriculum groups from CienciaVitae: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Search researchers across institutions via Ciência Vitae API
     * @param string $institutionName
     * @param int $page
     * @param int $pageSize
     * @return array|null
     */
    public function searchPersonsByInstitution($institutionName, $page = 1, $pageSize = 20)
    {
        try {
            $url = "searches/persons/institution";
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    'accept' => 'application/json',
                    'authorization' => $this->authorization,
                ],
                'query' => [
                    'institutionName' => $institutionName,
                    'page' => $page,
                    'pageSize' => $pageSize,
                ],
                'http_errors' => true,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            \Log::warning("Error searching persons by institution: " . $e->getMessage());
            return null;
        }
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
        $authenticusId = "";
        $researchGateProfile = "";
        $lattesId = "";

        $identifyingInfo =
            $responseArray["identifying-info"] ??
            $responseArray["identifyingInfo"] ??
            $responseArray["identifying_info"] ??
            [];

        $authorIdentifiersBlock =
            $identifyingInfo["author-identifiers"] ??
            $identifyingInfo["authorIdentifiers"] ??
            [];
        $authorIdentifiers =
            $authorIdentifiersBlock["author-identifier"] ??
            $authorIdentifiersBlock["authorIdentifier"] ??
            [];

        if (!empty($authorIdentifiers)) {
            foreach ($authorIdentifiers as $identifier) {
                    $identifierType = $identifier["identifier-type"] ?? [];
                    $identifierTypeCodeRaw =
                        ($identifierType["code"] ?? null) ??
                        ($identifierType["value"] ?? null) ??
                        ($identifierType["label"] ?? null) ??
                        ($identifierType["name"] ?? null) ??
                        ($identifier["identifier-type-code"] ?? null) ??
                        ($identifier["identifierType"]["code"] ?? null) ??
                        ($identifier["identifierType"]["value"] ?? null) ??
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

        $resumeValue = $identifyingInfo["resume"]["value"] ?? $identifyingInfo["resume"]["value"] ?? null;
        $lastModifiedDate = $responseArray["last-modified-date"] ?? $responseArray["lastModifiedDate"] ?? null;
        $privacyLevel =
            $identifyingInfo["person-info"]["photography"]["privacy-level"] ??
            $identifyingInfo["personInfo"]["photography"]["privacyLevel"] ??
            null;

        $authorObject->update([
                "orcid" => $orcid,
                "id_google_scholar" => $googleScholarId,
                "id_researcher" => $researcherId,
                "id_scopus_author" =>  $scopusId,
                "id_authenticus" => $authenticusId,
                "researchgate_profile" => $researchGateProfile,
                "id_lattes" => $lattesId,
                "profile_is_public" => 1,
                "resume" => $resumeValue,
                "profile_updated_date" => $lastModifiedDate ? date('Y-m-d', strtotime($lastModifiedDate)) : null,
                "profile_image_is_public" => ($privacyLevel == "publico")
            ]);

        $citationNamesBlock =
            $identifyingInfo["citation-names"] ??
            $identifyingInfo["citationNames"] ??
            null;

        if (!empty($citationNamesBlock)) {
            $citations = [];

            $citationNameItems =
                $citationNamesBlock["citation-name"] ??
                $citationNamesBlock["citationName"] ??
                [];

            foreach ($citationNameItems as $citationName) {
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

        $emailsBlock = $identifyingInfo["emails"] ?? null;

        if (!empty($emailsBlock)) {
            $authorObject->emails()->delete();

            $emailsToInsert = [];
            $seenEmails = [];
            $now = now();

            $emailItems = $emailsBlock["email"] ?? [];

            foreach ($emailItems as $emails) {
                $emailAddress =
                    $emails["emailAddress"] ??
                    $emails["email-address"] ??
                    $emails["email_address"] ??
                    $emails["address"] ??
                    $emails["value"] ??
                    null;
                $emailUseType =
                    $emails["emailType"]["value"] ??
                    $emails["email-type"]["value"] ??
                    $emails["emailType"]["label"] ??
                    $emails["emailType"] ??
                    null;

                $emailAddress = is_string($emailAddress) ? trim($emailAddress) : $emailAddress;

                if (!empty($emailAddress) && !isset($seenEmails[$emailAddress])) {
                    $seenEmails[$emailAddress] = true;
                    array_push($emailsToInsert, [
                        "email" => $emailAddress,
                        "use_type" => $emailUseType,
                        "author_id" => $authorObject->id,
                        "created_at" => $now,
                        "updated_at" => $now
                    ]);
                }
            }

            if (count($emailsToInsert) != 0) {
                $authorObject->emails()->insertOrIgnore($emailsToInsert);
            }
        }

        $phonesBlock =
            $identifyingInfo["phone-numbers"] ??
            $identifyingInfo["phoneNumbers"] ??
            null;

        if (!empty($phonesBlock)) {
            $authorObject->phones()->delete();

            $phonesToInsert = [];

            $phoneItems =
                $phonesBlock["phone-number"] ??
                $phonesBlock["phoneNumber"] ??
                [];

            foreach ($phoneItems as $phone) {
                $phoneNumber =
                    $phone["localNumber"] ??
                    $phone["local-number"] ??
                    $phone["local_number"] ??
                    $phone["internationalNumber"] ??
                    $phone["international-number"] ??
                    $phone["international_number"] ??
                    $phone["number"] ??
                    $phone["value"] ??
                    null;
                $phoneType =
                    $phone["phoneType"]["value"] ??
                    $phone["phoneType"]["label"] ??
                    $phone["type"]["value"] ??
                    $phone["type"] ??
                    null;
                $usageType =
                    $phone["usageType"]["value"] ??
                    $phone["usageType"]["label"] ??
                    $phone["usageType"] ??
                    null;
                $resolvedType = $phoneType ?? $usageType;

                if (!empty($phoneNumber)) {
                    array_push($phonesToInsert, [
                        "number" => $phoneNumber,
                        "type" => $resolvedType,
                        "use_type" => $usageType
                    ]);
                }
            }

            if (count($phonesToInsert) != 0) {
                $authorObject->phones()->createMany($phonesToInsert);
            }
        }

        $addressesBlock =
            $identifyingInfo["mailing-addresses"] ??
            $identifyingInfo["mailingAddresses"] ??
            null;

        if (!empty($addressesBlock)) {
            $authorObject->addresses()->delete();

            $addressesToInsert = [];

            $addressItems =
                $addressesBlock["mailing-address"] ??
                $addressesBlock["mailingAddress"] ??
                [];

            foreach ($addressItems as $address) {
                $street =
                    $address["streetAddress"] ??
                    $address["street-address"] ??
                    $address["street_address"] ??
                    $address["addressLine"] ??
                    $address["address-line"] ??
                    $address["address"] ??
                    null;
                $postalCode = $address["postalCode"] ?? $address["postal-code"] ?? $address["postal_code"] ?? null;
                $city = $address["city"] ?? null;
                $locality = $address["locality"] ?? null;
                $municipality = $address["municipality"] ?? null;
                if (!empty($locality) && !empty($municipality) && $locality !== $municipality) {
                    $city = trim($locality . ', ' . $municipality);
                } elseif (empty($city)) {
                    $city = $locality ?? $municipality;
                }
                $state = $address["provinceState"] ?? $address["state"] ?? $address["region"] ?? null;
                $country = $address["country"]["value"] ?? $address["country"] ?? null;
                $addressUseType =
                    $address["addressType"]["value"] ??
                    $address["addressType"]["label"] ??
                    $address["addressType"] ??
                    null;

                if (!empty($street) || !empty($postalCode) || !empty($city) || !empty($state) || !empty($country)) {
                    $streetValue = $street ?? '';
                    $postalCodeValue = $postalCode ?? '';
                    $cityValue = $city ?? '';
                    $stateValue = $state ?? '';
                    $countryValue = $country ?? '';
                    array_push($addressesToInsert, [
                        "adress" => $streetValue,
                        "postal_code" => $postalCodeValue,
                        "city" => $cityValue,
                        "state" => $stateValue,
                        "country" => $countryValue,
                        "use_type" => $addressUseType
                    ]);
                }
            }

            if (count($addressesToInsert) != 0) {
                $authorObject->addresses()->createMany($addressesToInsert);
            }
        }

        $websitesBlock =
            $identifyingInfo["websites"] ??
            $identifyingInfo["web-sites"] ??
            $identifyingInfo["website"] ??
            $identifyingInfo["web-site"] ??
            $identifyingInfo["webAddresses"] ??
            $identifyingInfo["web-addresses"] ??
            null;

        if (!empty($websitesBlock)) {
            $authorObject->websites()->delete();

            $rawItems =
                $websitesBlock["website"] ??
                $websitesBlock["web-site"] ??
                $websitesBlock["webAddress"] ??
                $websitesBlock["web-address"] ??
                $websitesBlock;

            if (!is_array($rawItems)) {
                $rawItems = [];
            }

            $items = array_values($rawItems);
            if (!empty($items) && array_keys($items) !== range(0, count($items) - 1)) {
                $items = [$items];
            }

            $websitesToInsert = [];

            foreach ($items as $item) {
                if (!is_array($item)) {
                    continue;
                }

                $url =
                    $item["url"] ??
                    $item["uri"] ??
                    $item["website-url"] ??
                    $item["websiteUrl"] ??
                    $item["value"] ??
                    null;

                $type =
                    $item["siteType"]["value"] ??
                    $item["siteType"]["label"] ??
                    $item["type"]["value"] ??
                    $item["type"]["label"] ??
                    $item["type"] ??
                    null;
                $websiteUseType =
                    $item["siteType"]["value"] ??
                    $item["siteType"]["label"] ??
                    $item["siteType"] ??
                    null;

                $label =
                    $item["label"] ??
                    $item["title"] ??
                    $item["description"] ??
                    null;

                $url = $this->sanitizeCvUrl($url);

                if (!empty($url)) {
                    $websitesToInsert[] = [
                        "url" => $url,
                        "type" => $type,
                        "label" => $label,
                        "use_type" => $websiteUseType,
                    ];
                }
            }

            if (count($websitesToInsert) != 0) {
                $authorObject->websites()->createMany($websitesToInsert);
            }
        }

        $languagesBlock =
            $identifyingInfo["language-competencies"] ??
            $identifyingInfo["languageCompetencies"] ??
            null;

        if (!empty($languagesBlock)) {
            $languagesToInsert = [];

            $languageItems =
                $languagesBlock["language-competency"] ??
                $languagesBlock["languageCompetency"] ??
                [];

            foreach ($languageItems as $languages) {
                $insertedOrExistinglanguage = Language::firstOrCreate(["language" => $languages["language"]["value"]]);

                $languagesToInsert[$insertedOrExistinglanguage->id] = [
                    "speech_level" => (($languages["speak"]["value"] ?? null)),
                    "writing_level" => (($languages["write"]["value"] ?? null)),
                    "listening_level" => (($languages["understand-spoken"]["value"] ?? null) ?? ($languages["understandSpoken"]["value"] ?? null)),
                    "peer_review_level" => (($languages["peer-review"]["value"] ?? null) ?? ($languages["peerReview"]["value"] ?? null)),
                    "read-level" => (($languages["read"]["value"] ?? null))
                ];
            }

            $authorObject->languages()->sync($languagesToInsert);
        }

        $domainsBlock =
            $identifyingInfo["domains-activity"] ??
            $identifyingInfo["domainsActivity"] ??
            null;

        if (!empty($domainsBlock)) {
            $domainActivities = [];

            $domainItems =
                $domainsBlock["domain-activity"] ??
                $domainsBlock["domainActivity"] ??
                [];

            foreach ($domainItems as $domainActivity) {
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
                    "author_id" => $authorObject->id,
                    "ciencia_vitae_funding_id" => $funding["id"] ?? null
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
            try {
                $authorObject->service()->delete();
            } catch (\Throwable $e) {
                \Log::warning('Service delete skipped due to lock error', [
                    'author_id' => $authorObject->id,
                    'error' => $e->getMessage()
                ]);
            }
            try {
                $this->removePolymorphicRelations($this->cienciaVitaeServiceAttrs, "service");
            } catch (\Throwable $e) {
                \Log::warning('Service cleanup skipped due to lock error', [
                    'author_id' => $authorObject->id,
                    'error' => $e->getMessage()
                ]);
            }

            foreach ($responseArray["services"]["service"] as $service) {
                $servicesAttributes = ($this->cienciaVitaeServiceAttrs[$service["service-category"]["code"]] ?? null);

                if ($servicesAttributes) {
                    $serviceInformation = $service[$servicesAttributes["attribute"]];
                    $serviceType = ServiceType::firstOrCreate(["name" => $service["service-category"]["value"]]);
                    $serviceData = $this->serviceData($servicesAttributes["attribute"], $serviceInformation);
                    foreach ($serviceData as $key => $value) {
                        if (is_string($value)) {
                            $sanitized = $this->sanitizeCvText($value);
                            $serviceData[$key] = $this->truncateCvString($sanitized ?? '', 255);
                        }
                    }

                    $newPolymorphicService = new $servicesAttributes["table-class"]($serviceData);
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

        // Distinctions & Awards (Prémios e Distinções)
        try {
            $distinctionsBlock = $responseArray["distinctions"] ?? null;
            if (!$distinctionsBlock && !empty($authorObject->userInformation?->ciencia_vitae)) {
                $distinctionsBlock = $this->getDistinctions($authorObject->userInformation->ciencia_vitae);
            }
            if (!empty($distinctionsBlock)) {
                $authorObject->distinctions()->delete();
                $rawDistinctions = $distinctionsBlock["distinction"] ?? $distinctionsBlock["distinctions"] ?? $distinctionsBlock;
                if (isset($rawDistinctions["distinction-name"]) || isset($rawDistinctions["distinctionName"]) || isset($rawDistinctions["distinction-type"])) {
                    $rawDistinctions = [$rawDistinctions];
                }
                if (is_array($rawDistinctions)) {
                    $distinctionsToInsert = [];
                    foreach ($rawDistinctions as $dist) {
                        if (!is_array($dist)) continue;
                        $distName = $this->sanitizeCvText(
                            $dist["distinction-name"] ??
                            $dist["distinctionName"] ??
                            $dist["name"] ??
                            $dist["value"] ??
                            ""
                        );
                        if (empty($distName)) continue;

                        $distType = $this->sanitizeCvText(
                            $dist["distinction-type"]["value"] ??
                            $dist["distinction-type"]["label"] ??
                            $dist["distinctionType"]["value"] ??
                            $dist["distinctionType"]["label"] ??
                            $dist["distinction-type"] ??
                            ""
                        );

                        $effectiveDate = $dist["effective-date"] ?? $dist["effectiveDate"] ?? $dist["date"] ?? [];
                        $effYear = is_array($effectiveDate) ? ($effectiveDate["year"] ?? null) : null;
                        $effMonth = is_array($effectiveDate) ? ($effectiveDate["month"] ?? null) : null;
                        $effDay = is_array($effectiveDate) ? ($effectiveDate["day"] ?? null) : null;
                        if (!$effYear && is_string($effectiveDate) && preg_match('/^(\d{4})/', $effectiveDate, $m)) {
                            $effYear = $m[1];
                        }

                        $instName = null;
                        if (isset($dist["institutions"]["institution"])) {
                            $instList = $dist["institutions"]["institution"];
                            $instFirst = is_array($instList) && isset($instList[0]) ? $instList[0] : $instList;
                            $instName = $this->sanitizeCvText($instFirst["institution-name"] ?? $instFirst["institutionName"] ?? $instFirst["name"] ?? null);
                        } elseif (isset($dist["institution"])) {
                            $instName = $this->sanitizeCvText($dist["institution"]["institution-name"] ?? $dist["institution"]["name"] ?? null);
                        }

                        $countryVal = $this->sanitizeCvText(
                            $dist["country"]["value"] ??
                            $dist["country"]["label"] ??
                            $dist["country"] ??
                            null
                        );

                        $distinctionsToInsert[] = [
                            'distinction_type' => $this->truncateCvString($distType, 255),
                            'distinction_name' => $this->truncateCvString($distName, 255),
                            'effective_year' => $effYear ? substr((string)$effYear, 0, 4) : null,
                            'effective_month' => $effMonth ? substr((string)$effMonth, 0, 2) : null,
                            'effective_day' => $effDay ? substr((string)$effDay, 0, 2) : null,
                            'institution_name' => $this->truncateCvString($instName, 255),
                            'country' => $this->truncateCvString($countryVal, 255),
                            'description' => $this->sanitizeCvText($dist["description"] ?? null),
                        ];
                    }

                    if (!empty($distinctionsToInsert)) {
                        $authorObject->distinctions()->createMany($distinctionsToInsert);
                    }
                }
            }
        } catch (\Throwable $e) {
            \Log::warning('Distinctions processing skipped: ' . $e->getMessage());
        }

        if (isset($responseArray["outputs"])) {
            $publicationsAdded = [];
            $cvOutputIdsSeen = [];
            $outputProcessingHadError = false;

            \Log::info('Starting outputs processing', [
                'total_outputs' => count($responseArray["outputs"]["output"])
            ]);
            
            $authorCitationNames = $authorObject->citationName()
                ->pluck('citation_name')
                ->map(fn ($name) => trim((string) $name))
                ->filter()
                ->values();
            $authorCitationNamesLower = $authorCitationNames
                ->map(fn ($name) => strtolower($name))
                ->values();

            foreach ($responseArray["outputs"]["output"] as $output) {
                $publicationsAttributes = ($this->outputs[$output["output-type"]["code"]]) ?? null;

                \Log::info('Processing output', [
                    'output_id' => $output["id"],
                    'type_code' => $output["output-type"]["code"],
                    'has_attributes' => !is_null($publicationsAttributes)
                ]);

                if ($publicationsAttributes) {
                    $cvOutputIdsSeen[] = $output["id"];

                    try {
                        $publicationTypeAttribute = $publicationsAttributes["attribute"];
                        $publicationTitleAttribute = $publicationsAttributes["title-atribute-name"];
                        $publicationInformation = $output[$publicationTypeAttribute];
                        $doi = null;
                        $isbn = null;
                        $issn = null;
                        $handle = null;
                        $pmid = null;

                        if (isset($publicationInformation["identifiers"])) {
                            $rawIdentifiers = $publicationInformation["identifiers"]["identifier"] ?? $publicationInformation["identifiers"] ?? [];
                            if (isset($rawIdentifiers["identifier-type"]) || isset($rawIdentifiers["identifierType"]) || isset($rawIdentifiers["identifier"])) {
                                $rawIdentifiers = [$rawIdentifiers];
                            }
                            if (is_array($rawIdentifiers)) {
                                foreach ($rawIdentifiers as $identifier) {
                                    if (!is_array($identifier)) {
                                        continue;
                                    }
                                    $idTypeCode = strtoupper(trim((string) (
                                        $identifier["identifier-type"]["code"] ??
                                        $identifier["identifierType"]["code"] ??
                                        $identifier["identifier-type"]["value"] ??
                                        $identifier["identifierType"]["value"] ??
                                        ""
                                    )));
                                    $idVal = trim((string) ($identifier["identifier"] ?? $identifier["value"] ?? ""));
                                    if ($idVal === "") {
                                        continue;
                                    }
                                    if ($idTypeCode === 'DOI' && empty($doi)) {
                                        $doi = $idVal;
                                    } elseif ($idTypeCode === 'ISBN' && empty($isbn)) {
                                        $isbn = $idVal;
                                    } elseif ($idTypeCode === 'ISSN' && empty($issn)) {
                                        $issn = $idVal;
                                    } elseif (($idTypeCode === 'HANDLE' || $idTypeCode === 'URI' || $idTypeCode === 'URL' || $idTypeCode === 'REPOSITORY') && empty($handle)) {
                                        $handle = $idVal;
                                    } elseif ($idTypeCode === 'PMID' && empty($pmid)) {
                                        $pmid = $idVal;
                                    }
                                }
                            }
                        }

                        if (isset($publicationInformation["authors"])) {
                            \Log::info('Processing authors', [
                                'author_count' => count($publicationInformation["authors"]["author"])
                            ]);
                            $citationName = null;
                            $authorCvId = trim((string) ($authorObject->userInformation->ciencia_vitae ?? ''));
                            foreach ($publicationInformation["authors"]["author"] as $authorData) {
                                $candidateName = trim((string) ($authorData["value"] ?? ""));
                                if ($candidateName === '') {
                                    continue;
                                }

                                $candidateCvId = trim((string) (
                                    $authorData["ciencia-vitae-id"] ??
                                    $authorData["ciencia-vitae"] ??
                                    $authorData["cv-id"] ??
                                    ""
                                ));

                                $matchesAuthor = false;
                                if ($authorCvId !== '' && $candidateCvId !== '' && strcasecmp($authorCvId, $candidateCvId) === 0) {
                                    $matchesAuthor = true;
                                } elseif ($authorCitationNamesLower->contains(strtolower($candidateName))) {
                                    $matchesAuthor = true;
                                }

                                if ($matchesAuthor) {
                                    $citationName = $candidateName;
                                    break;
                                }
                            }

                            if ($citationName === null && $authorCitationNames->isNotEmpty()) {
                                $citationName = $authorCitationNames->first();
                            }

                            if ($citationName === null) {
                                \Log::warning('No matching citation name for author output', [
                                    'author_id' => $authorObject->id,
                                    'output_id' => $output["id"]
                                ]);
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

                            $title = $this->sanitizeCvText($publicationInformation[$publicationTitleAttribute] ?? "");
                            if ($title === null) {
                                $title = "";
                            }
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
                                    if (is_array($value)) {
                                        $value = $this->normalizeCvScalar($value);
                                    }

                                    if (is_string($value)) {
                                        $value = $this->sanitizeCvText($value);
                                    }

                                    if ($this->isUrlField($key)) {
                                        $value = $this->sanitizeCvUrl($value);
                                    }

                                    if (is_string($value) && strlen($value) > 255) {
                                        $value = substr($value, 0, 255);
                                    }

                                    $dataToUpdate[$key] = $value;
                                }

                                if (array_key_exists('encyclopedia_title', $dataToUpdate)) {
                                    $titleValue = $dataToUpdate['encyclopedia_title'] ?? null;
                                    if ($titleValue === null || $titleValue === '') {
                                        $fallback = $this->sanitizeCvText($dataToUpdate['entry_title'] ?? null);
                                        $dataToUpdate['encyclopedia_title'] = $fallback ?? 'Unknown';
                                    }
                                }

                                $dataToUpdate = array_filter(
                                    $dataToUpdate,
                                    fn($key) => in_array($key, $tableColumns),
                                    ARRAY_FILTER_USE_KEY
                                );

                                $publication->polymorphic()->update($dataToUpdate);

                                $citationString = $this->truncateCvString(
                                    $this->returnValueIfNotNull($publicationInformation, "authors", "citation"),
                                    255
                                );

                                $publication->update([
                                    "title" => $title,
                                    "doi" => ($doi) ?? null,
                                    "isbn" => ($isbn) ?? null,
                                    "issn" => ($issn) ?? null,
                                    "handle" => ($handle) ?? null,
                                    "pmid" => ($pmid) ?? null,
                                    "citation_string" => $citationString,
                                    "year" => $this->returnValueIfNotNull($publicationInformation, $publicationsAttributes["year-column"], "year")
                                ]);

                                \Log::info('Updated existing publication', ['id' => $publication->id]);
                            } else {
                                $publicationType = OutputType::firstOrCreate(["name" => $output["output-type"]["value"]]);

                                $publicationData = $this->getPublicationData($publicationTypeAttribute, $publicationInformation);
                                if (!is_array($publicationData)) {
                                    \Log::warning('Publication data is not an array', [
                                        'author_id' => $authorObject->id,
                                        'output_id' => $output["id"],
                                        'type' => $publicationTypeAttribute
                                    ]);
                                    $publicationData = [];
                                }

                                foreach ($publicationData as $key => $value) {
                                    if (is_array($value)) {
                                        $value = $this->normalizeCvScalar($value);
                                    }

                                    if (is_string($value)) {
                                        $value = $this->sanitizeCvText($value);
                                    }

                                    if ($this->isUrlField($key)) {
                                        $value = $this->sanitizeCvUrl($value);
                                    }

                                    if ($key === 'encyclopedia_title' && ($value === null || $value === '')) {
                                        $fallback = $this->sanitizeCvText($publicationData['entry_title'] ?? null);
                                        $value = $fallback ?? 'Unknown';
                                    }

                                    if (is_string($value) && strlen($value) > 255) {
                                        $value = substr($value, 0, 255);
                                    }

                                    $publicationData[$key] = $value;
                                }

                                if (array_key_exists('encyclopedia_title', $publicationData)) {
                                    $titleValue = $publicationData['encyclopedia_title'] ?? null;
                                    if ($titleValue === null || $titleValue === '') {
                                        $fallback = $this->sanitizeCvText($publicationData['entry_title'] ?? null);
                                        $publicationData['encyclopedia_title'] = $fallback ?? 'Unknown';
                                    }
                                }

                                $newPolymorphicPublication = new $publicationsAttributes["table-class"]($publicationData);
                                $newPolymorphicPublication->save();

                                $citationString = $this->truncateCvString(
                                    $this->returnValueIfNotNull($publicationInformation, "authors", "citation"),
                                    255
                                );

                                $publication = $newPolymorphicPublication->output()->create([
                                    "title" => $title,
                                    "doi" => ($doi) ?? null,
                                    "isbn" => ($isbn) ?? null,
                                    "issn" => ($issn) ?? null,
                                    "handle" => ($handle) ?? null,
                                    "pmid" => ($pmid) ?? null,
                                    "type_id" => $publicationType->id,
                                    "citation_string" => $citationString,
                                    "year" => $this->returnValueIfNotNull($publicationInformation, $publicationsAttributes["year-column"], "year"),
                                    "ciencia_vitae_pub_id" => $output["id"]
                                ]);

                                \Log::info('Created new publication', ['id' => $publication->id]);
                            }

                            array_push($publicationsAdded, $output["id"]);

                            $citationId = (int) ($citation->id ?? 0);
                            if ($citationId > 0) {
                                try {
                                    $publication->citations()->syncWithoutDetaching([
                                        $citationId => ["author_id" => $authorObject->id]
                                    ]);
                                } catch (\Throwable $e) {
                                    \Log::warning('Citation sync skipped due to invalid IDs', [
                                        'publication_id' => $publication->id,
                                        'citation_id' => $citationId,
                                        'error' => $e->getMessage()
                                    ]);
                                }
                            }

                            \Log::info('Synced citation', [
                                'publication_id' => $publication->id,
                                'citation_id' => $citation->id
                            ]);

                            try {
                                $this->insertKeywords($publication, $publicationInformation);
                            } catch (\Throwable $e) {
                                \Log::warning('Keyword insert skipped due to error', [
                                    'publication_id' => $publication->id,
                                    'error' => $e->getMessage()
                                ]);
                            }
                        }
                    } catch (\Throwable $e) {
                        $outputProcessingHadError = true;
                        \Log::error('Output processing failed', [
                            'author_id' => $authorObject->id,
                            'output_id' => $output["id"],
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                    }
                }
            }

            \Log::info('Publications added', [
                'count' => count($publicationsAdded),
                'ids' => $publicationsAdded
            ]);

            $cvOutputIdsSeen = array_values(array_unique($cvOutputIdsSeen));

            if (count($cvOutputIdsSeen) > 0 && !$outputProcessingHadError) {
                $outputIdsToDelete = $authorObject->output()
                    ->whereNotIn("ciencia_vitae_pub_id", $cvOutputIdsSeen)
                    ->pluck('outputs.id');

                if ($outputIdsToDelete->isNotEmpty()) {
                    Output::whereIn('id', $outputIdsToDelete)->delete();
                }

                $this->removePolymorphicRelations($this->outputs, "output");
            }
        }

        // Groups (Funding <-> Output links)
        try {
            $groups = $responseArray["groups"] ?? null;
            if (!$groups && !empty($authorObject->userInformation?->ciencia_vitae)) {
                $groups = $this->getCurriculumGroups($authorObject->userInformation->ciencia_vitae);
            }
            if ($groups) {
                $rawGroups = $groups["fundings-outputs"]["funding-output"]
                    ?? $groups["fundingsOutputs"]
                    ?? $groups["groups"]["fundingsOutputs"]
                    ?? [];
                if (isset($rawGroups["funding"]) || isset($rawGroups["output"])) {
                    $rawGroups = [$rawGroups];
                }
                if (is_array($rawGroups)) {
                    foreach ($rawGroups as $grp) {
                        if (!is_array($grp)) continue;
                        $fId = (string) ($grp["funding"] ?? $grp["funding-id"] ?? "");
                        $oId = (string) ($grp["output"] ?? $grp["output-id"] ?? "");
                        if ($fId !== "" && $oId !== "") {
                            $proj = $authorObject->project()->where('ciencia_vitae_funding_id', $fId)->first();
                            $pub = $authorObject->output()->where('ciencia_vitae_pub_id', $oId)->first();
                            if ($proj && $pub) {
                                $proj->outputs()->syncWithoutDetaching([$pub->id]);
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            \Log::warning('Funding-output groups sync skipped: ' . $e->getMessage());
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
                        "start_page" => $this->normalizeCvInt($data["page-range-from"] ?? null),
                        "end_page" => $this->normalizeCvInt($data["page-range-to"] ?? null),
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
                        "start_page" => $this->normalizeCvInt($data["page-range-from"] ?? null),
                        "end_page" => $this->normalizeCvInt($data["page-range-to"] ?? null),
                        'city' => (($data["publication-location"]["city"]) ?? null),
                        'publisher' => "",
                        'open_access' => ($data["open-access"] ?? null),
                        'status' => ($data["publication-status"]["value"] ?? null),
                        "publication_year" => ($data["publication-date"]["year"]) ?? null,
                        "publication_month" => ($data["publication-date"]["month"]) ?? null,
                        "publication_day" => ($data["publication-date"]["day"]) ?? null,
                        'country' => $this->getNestedValue($data, ["publication-location", "country", "value"]) ?? $this->normalizeCvScalar($data["publication-location"] ?? null),
                        'role' => ($data["authoring-role"]["value"] ?? null),
                        'url' => ($data["url"] ?? null),
                        "refreed" => ($data["refereed"] ?? null)
                    ];
                    break;
                case "journal-issue":
                    $array = [
                        "issue_title" => ($data["issue-title"] ?? null),
                        "journal" => ($data["journal"] ?? null),
                        "volume" => ($data["volume"] ?? null),
                        "issue_number" => ($data["issue-number"] ?? null),
                        "number_of_pages" => $this->normalizeCvInt($data["number-of-pages"] ?? null),
                        "refereed" => ($data["refereed"] ?? null),
                        "publication_status" => ($data["publication-status"]["value"] ?? null),
                        "publication_date" => ($data["publication-date"]["year"] ?? null),
                        "publication_location" => $this->getNestedValue($data, ["publication-location", "country", "value"]) ?? $this->normalizeCvScalar($data["publication-location"] ?? null),
                        "editing_role" => ($data["editing-role"]["value"] ?? null),
                        "url" => ($data["url"] ?? null),
                    ];
                    break;
                case "encyclopedia-entry":
                    $encyclopediaTitle = $this->sanitizeCvText($data["encyclopedia"] ?? null)
                        ?? $this->sanitizeCvText($data["entry-title"] ?? null)
                        ?? '';
                    $array = [
                        "entry_title" => ($data["entry-title"] ?? null),
                        "encyclopedia_title" => $encyclopediaTitle,
                        "volume" => $this->normalizeCvInt($data["volume"] ?? null),
                        "number_of_volumes" => $this->normalizeCvInt($data["number-of-volumes"] ?? null),
                        "edition" => ($data["edition"] ?? null),
                        "page_range_from" => $this->normalizeCvInt($data["page-range-from"] ?? null),
                        "page_range_to" => $this->normalizeCvInt($data["page-range-to"] ?? null),
                        "publication_status" => ($data["publication-status"]["value"] ?? null),
                        "publication_year" => ($data["publication-date"]["year"] ?? null),
                        "publication_location" => $this->getNestedValue($data, ["publication-location", "country", "value"]) ?? $this->normalizeCvScalar($data["publication-location"] ?? null),
                        "publisher" => ($data["publisher"] ?? null),
                        "autoring_role" => ($data["authoring-role"]["value"] ?? null),
                        "url" => ($data["url"] ?? null),
                    ];
                    break;
                case "newsletter-article":
                    $array = [
                        "article_title" => $this->truncateCvString($data["article-title"] ?? null, 255),
                        "newsletter" => $this->truncateCvString($data["newsletter"] ?? null, 255),
                        "volume" => $this->normalizeCvInt($data["volume"] ?? null),
                        "issue" => $this->normalizeCvScalar($data["issue"] ?? null),
                        "page_range_from" => $this->normalizeCvInt($data["page-range-from"] ?? null),
                        "page_range_to" => $this->normalizeCvInt($data["page-range-to"] ?? null),
                        "publication_date" => $this->formatCvDate($data["publication-date"] ?? null),
                        "publication_location" => $this->getNestedValue($data, ["publication-location", "country", "value"]) ?? $this->normalizeCvScalar($data["publication-location"] ?? null),
                        "url" => $this->normalizeCvScalar($data["url"] ?? null),
                        "research_classifications" => $this->normalizeCvScalar($data["research-classifications"] ?? null),
                    ];
                    break;
                case "newspapper-article":
                    $array = [
                        "article_title" => $this->truncateCvString($data["article-title"] ?? null, 255),
                        "newspaper" => $this->truncateCvString($data["newspaper"] ?? null, 255),
                        "section" => $this->normalizeCvScalar($data["section"] ?? null),
                        "volume" => $this->normalizeCvInt($data["volume"] ?? null),
                        "edition" => $this->normalizeCvScalar($data["edition"] ?? null),
                        "page_range_from" => $this->normalizeCvInt($data["page-range-from"] ?? null),
                        "page_range_to" => $this->normalizeCvInt($data["page-range-to"] ?? null),
                        "publication_date" => ($data["publication-date"]["year"] ?? null),
                        "publication_location" => $this->getNestedValue($data, ["publication-location", "country", "value"]) ?? $this->normalizeCvScalar($data["publication-location"] ?? null),
                        "url" => $this->normalizeCvScalar($data["url"] ?? null),
                        "research_classifications" => $this->normalizeCvScalar($data["research-classifications"] ?? null),
                    ];
                    break;
                case "conference-abstract":
                    $array = [
                        "article_title" => $data["article-title"] ?? null,
                        // "section" => $data["section"] ?? null,
                        "volume" => $this->normalizeCvInt($data["volume"] ?? null),
                        // "edition" => $data["edition"] ?? null,
                        "page_range_from" => $this->normalizeCvInt($data["page-range-from"] ?? null),
                        "page_range_to" => $this->normalizeCvInt($data["page-range-to"] ?? null),
                        "publication_date" => ($data["publication-date"]["year"] ?? null),
                        // "publication_location" => ($data["publication-location"]["country"]["value"] ?? null),
                    ];
                    break;
                case "test":
                    $array = [
                        "title" => ($data["title"] ?? null),
                        "date_first_used" => ($data["date-first-used"]["year"] ?? null),
                    ];
                    break;
                case "working-paper":
                    $array = [
                        "title" => ($data["title"] ?? null),
                        "volume" => ($data["volume"] ?? null),
                        "publication_date" => $this->formatCvDate($data["publication-date"] ?? null),
                        "url" => ($data["url"] ?? null),
                    ];
                    break;
                case "online-resource":
                    $array = [
                        "title" => ($data["title"] ?? null),
                        "creation_date" => $this->formatCvDate($data["creation-date"] ?? null),
                        "url" => ($data["url"] ?? null),
                    ];
                    break;
                case "magazine-article":
                    $array = [
                        "magazine" => ($data["magazine"]) ?? null,
                        'role' => ($data["authoring-role"]["value"] ?? null),
                        'url' => ($data["url"] ?? null),
                        "issue" => ($data["issue"] ?? null),
                        "start_page" => $this->normalizeCvInt($data["page-range-from"] ?? null),
                        "end_page" => $this->normalizeCvInt($data["page-range-to"] ?? null),
                        "volume" => ($data["volume"] ?? null),
                        "publication_year" => ($data["publication-date"]["year"]) ?? null,
                        "publication_month" => ($data["publication-date"]["month"]) ?? null,
                        "publication_day" => ($data["publication-date"]["day"]) ?? null,
                        "pub_country" => (($data["publication-location"]["country"]["value"]) ?? null),
                        "pub_city" => (($data["publication-location"]["city"]) ?? null),
                    ];
                    break;
                case "exhibition-catalogue":
                    $array = [
                        "title" => ($data["title"] ?? null),
                        "number_of_pages" => ($data["number-of-pages"] ?? null),
                        "publication_year" => ($data["publication-date"]["year"] ?? null),
                        "gallery_or_publisher" => ($data["gallery-or-publisher"] ?? null),
                    ];
                    break;
                case "preface-postface":
                    $array = [
                        "preface_postface_type" => $data["preface-postface-type"] ?? null,
                        "preface_postface_title" => $data["preface-postface-title"] ?? null,
                        "book_title" => $data["book-title"] ?? null,
                        "book_volume" => $data["book-volume"] ?? null,
                        "book_edition" => $data["book-edition"] ?? null,
                        "preface_postface_page_range_from" => $this->normalizeCvInt($data["preface-postface-page-range-from"] ?? null),
                        "preface_postface_page_range_to" => $this->normalizeCvInt($data["preface-postface-page-range-to"] ?? null),
                        "refereed" => $data["refereed"] ?? null,
                        "publication_status" => $data["publication-status"]["value"] ?? null,
                        "publication_year" => $data["publication-year"] ?? null,
                        "publication_location" => $this->getNestedValue($data, ["publication-location", "country", "value"]) ?? $this->normalizeCvScalar($data["publication-location"] ?? null),
                        "book_publisher" => $data["book-publisher"] ?? null,
                        "authoring_role" => $data["authoring-role"]["value"] ?? null,
                        "url" => $data["url"] ?? null,
                    ];
                    break;
                case "preprint":
                    $array = [
                        "title" => $data["title"] ?? null,
                        "volume" => $data["volume"] ?? null,
                        "journal" => $data["journal"] ?? null,
                        "submission_location" => $data["submission-location"] ?? null,
                        "date_submitted" => $this->formatCvDate($data["date-submitted"] ?? null),
                        "url" => $data["url"] ?? null,
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
                case "book-review":
                    $array = [
                        "review_title" => ($data["review-title"] ?? null),
                        "published_in" => ($data["published-in"] ?? null),
                        "review_volume" => $this->normalizeCvScalar($data["review-volume"] ?? null),
                        "review_issue" => $this->normalizeCvScalar($data["review-issue"] ?? null),
                        "start_page" => ($data["review-page-range-from"] ?? null),
                        "end_page" => ($data["review-page-range-to"] ?? null),
                        "refereed" => $data["refereed"] ?? null,
                        "publication_status" => $data["publication-status"]["value"] ?? null,
                        "review_publication" => $this->normalizeCvScalar($data["review-publication"] ?? null),
                        "date_of_review_publication" => $data["date-of-review-publication"]["year"] ?? null,
                        "review_publisher" => $this->normalizeCvScalar($data["review-publisher"] ?? null),
                        "url" => $this->normalizeCvScalar($data["url"] ?? null),
                        "book_title" => $data["book-title"] ?? null,
                        "book_volume" => $this->normalizeCvScalar($data["book-volume"] ?? null),
                        "book_edition" => $this->normalizeCvScalar($data["book-edition"] ?? null),
                        "book_refereed" => $data["book-refereed"] ?? null,
                        "book_publication_year" => $data["book-publication-year"] ?? null,
                        "book_publication_location" => $this->getNestedValue($data, ["book-publication-location", "country", "value"]) ?? $this->normalizeCvScalar($data["book-publication-location"] ?? null),
                    ];
                    break;
                case "edited-book":
                    $array = [
                        "title" => $data["title"] ?? null,
                        "volume" => $data["volume"] ?? null,
                        "edition" => $data["edition"] ?? null,
                        "number_of_pages" => $data["number-of-pages"] ?? null,
                        "refereed" => $data["refereed"] ?? null,
                        "status" => $data["publication-status"]["value"] ?? null,
                        "publication_year" => $data["publication-year"] ?? null,
                        "pub_country" => $this->getNestedValue($data, ["publication-location", "country", "value"]) ?? $this->normalizeCvScalar($data["publication-location"] ?? null),
                        "pub_city" => $this->getNestedValue($data, ["publication-location", "city"]) ?? null,
                        "publisher" => $data["publisher"] ?? null,
                        "editing_role" => $data["editing-role"]["value"] ?? null,
                        "url" => $data["url"] ?? null,
                    ];
                    break;
                case "book-chapter":
                    $array = [
                        "book_title" => ($data["book-title"]) ?? null,
                        "book_volume" => ($data["book-volume"]) ?? null,
                        "book_edition" => ($data["book-edition"]) ?? null,
                        "chapter_start_page" => $this->normalizeCvInt($data["chapter-page-range-from"] ?? null),
                        "chapter_end_page" => $this->normalizeCvInt($data["chapter-page-range-to"] ?? null),
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
                case "translation":
                    $array = [
                        "title" => ($data["title"] ?? null),
                        "series_title" => ($data["series-title"] ?? null),
                        "volume" => ($data["volume"] ?? null),
                        "number_of_volumes" => ($data["number-of-volumes"] ?? null),
                        "edition" => ($data["edition"] ?? null),
                        "number_of_pages" => ($data["number-of-pages"] ?? null),
                        "publication_status" => ($data["publication-status"]["value"] ?? null),
                        "publication_year" => ($data["publication-year"] ?? null),
                        "publication_location" => ($data["publication-location"]["country"]["value"] ?? null),
                        "publisher" => ($data["publisher"] ?? null),
                        "url" => ($data["url"] ?? null)
                    ];
                    break;
                case "manual":
                    $array = [
                        "title" => ($data["title"] ?? null),
                        "series_title" => ($data["series-title"] ?? null),
                        "volume" => ($data["volume"] ?? null),
                        "number_of_volumes" => $this->normalizeCvInt($data["number-of-volumes"] ?? null),
                        "edition" => ($data["edition"] ?? null),
                        "number_of_pages" => $this->normalizeCvInt($data["number-of-pages"] ?? null),
                        "publication_status" => ($data["publication-status"]["value"] ?? null),
                        "publication_year" => $this->normalizeCvInt($data["publication-year"] ?? null),
                        "publication_location" => $this->getNestedValue($data, ["publication-location", "country", "value"]) ?? $this->normalizeCvScalar($data["publication-location"] ?? null),
                        "publisher" => ($data["publisher"] ?? null),
                        "authoring_role" => ($data["authoring-role"]["value"] ?? null),
                        "url" => ($data["url"] ?? null),
                    ];
                    break;
                case "report":
                    $array = [
                        "report_title" => ($data["report-title"] ?? null),
                        "volume" => ($data["volume"] ?? null),
                        "number_of_pages" => $this->normalizeCvInt($data["number-of-pages"] ?? null),
                        "institution" => $this->normalizeCvScalar($data["institution"] ?? null),
                        "date_submitted" => $this->formatCvDate($data["date-submitted"] ?? null),
                        "authoring_role" => ($data["authoring-role"]["value"] ?? null),
                        "publication_status" => ($data["publication-status"]["value"] ?? null),
                        "url" => ($data["url"] ?? null),
                    ];
                    break;
                case "website":
                    $array = [
                        "title" => ($data["title"] ?? null),
                        "description" => ($data["description"] ?? null),
                        "launch_date" => $this->formatCvDate($data["launch-date"] ?? null),
                        "url" => ($data["url"] ?? null),
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
        if (!is_array($data)) {
            return [];
        }

        switch ($serviceType) {
            case "event-administration":
                $eventDescription = $this->sanitizeCvAscii($this->returnValueIfNotNull($data, "event-description"));
                $eventType = $this->sanitizeCvAscii($this->returnValueIfNotNull($data, "event-type", "value"));
                $adminRole = $this->sanitizeCvAscii($this->returnValueIfNotNull($data, "administrative-role", "value"));
                $array = [
                    "activity_start_year" => $this->returnValueIfNotNull($data, "activity-start-date", "year"),
                    "activity_start_month" => $this->returnValueIfNotNull($data, "activity-start-date", "month"),
                    "activity_start_day" => $this->returnValueIfNotNull($data, "activity-start-date", "day"),
                    "activity_end_year" => $this->returnValueIfNotNull($data, "activity-end-date", "year"),
                    "activity_end_month" => $this->returnValueIfNotNull($data, "activity-end-date", "month"),
                    "activity_end_day" => $this->returnValueIfNotNull($data, "activity-end-date", "day"),
                    "event_description" => $this->truncateCvString($eventDescription ?? 'Unknown', 255),
                    "event_type" => $this->truncateCvString($eventType ?? null, 255),
                    "administrative_role" => $this->truncateCvString($adminRole ?? null, 255)
                ];
                break;
            case "event-participation":
                $eventDescription = $this->sanitizeCvAscii($this->returnValueIfNotNull($data, "event-description"));
                $eventName = $this->sanitizeCvAscii($this->returnValueIfNotNull($data, "event-name"));
                $eventType = $this->sanitizeCvAscii($this->returnValueIfNotNull($data, "event-type", "value"));
                $array = [
                    "event_description" => $this->truncateCvString($eventDescription ?? 'Unknown', 255),
                    "event_name" => $this->truncateCvString($eventName ?? null, 255),
                    "event_type" => $this->truncateCvString($eventType ?? null, 255),
                    "start_date_year" => $this->returnValueIfNotNull($data, "startDate", "year"),
                    "start_date_month" => $this->returnValueIfNotNull($data, "startDate", "month"),
                    "start_date_day" => $this->returnValueIfNotNull($data, "startDate", "day"),
                    "end_date_year" => $this->returnValueIfNotNull($data, "endDate", "year"),
                    "end_date_month" => $this->returnValueIfNotNull($data, "endDate", "month"),
                    "end_date_day" => $this->returnValueIfNotNull($data, "endDate", "day"),
                ];
                break;
            case "committee-membership":
                $committeeName = $this->sanitizeCvText($this->returnValueIfNotNull($data, "committee-name"));
                $membershipType = $this->sanitizeCvText($this->returnValueIfNotNull($data, "membership-type", "value"));
                $array = [
                    "committee_name" => $this->truncateCvString($committeeName ?? 'Unknown', 255),
                    "membership_type" => $this->truncateCvString($membershipType ?? null, 255),
                ];
                break;
            case "journal-reviewing-refereeing":
                $array = [
                    "journal" => $this->truncateCvString($this->returnValueIfNotNull($data, "journal", "value"), 255),
                    "press" => $this->truncateCvString($this->returnValueIfNotNull($data, "press"), 255),
                    "works_reviewed" =>  $this->returnValueIfNotNull($data, "works-reviewed"),
                    "url" => $this->returnValueIfNotNull($data, "url"),
                ];
                break;
            case "conference-reviewing-refereeing":
                $array = [
                    "conference" => $this->truncateCvString($this->returnValueIfNotNull($data, "conference"), 255),
                    "conference_host" => $this->truncateCvString($this->returnValueIfNotNull($data, "conference-host"), 255),
                    "works_reviewed" => $this->returnValueIfNotNull($data, "works-reviewed")
                ];
                break;
            case "research-based-degree-supervision":
                // dd($this->returnValueIfNotNull($data, "thesis-title"));
                $array = [
                    "thesis_title" => $this->truncateCvString($this->returnValueIfNotNull($data, "thesis-title"), 255),
                    "supervisory_title" => $this->getNestedValue($data, ['supervisory-type', 'value'])
                        ?? $this->normalizeCvScalar($data['supervisory-type'] ?? null),
                    "start_date" => $this->getNestedValue($data, ['start-date', 'year']) ?? '',
                    "end_date" => $this->getNestedValue($data, ['end-date', 'year']) ?? '',
                ];
                break;
            case "graduate-examination":
                $theme = $this->sanitizeCvText($this->returnValueIfNotNull($data, "theme"));
                $examSubject = $this->sanitizeCvText($data['examination-subject'] ?? null);
                $array = [
                    "theme" => $this->truncateCvString($theme ?? 'Unknown', 255),
                    "examination_subject" => $this->truncateCvString($examSubject ?? null, 255),
                    "start_date" => $this->getNestedValue($data, ['date', 'year']) ?? '',
                    "end_date" => $this->getNestedValue($data, ['date', 'year']) ?? '',
                    "year" => $this->getNestedValue($data, ['date', 'year']) ?? '',

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
            $keywordItems = $data["keywords"]["keyword"] ?? [];

            if (!is_array($keywordItems)) {
                $keywordItems = [$keywordItems];
            }

            foreach ($keywordItems as $keyword) {
                $treatedKeyWords = StringTreatmentController::explodeAndReplaceSpecificCharacters((string) $keyword);

                foreach ($treatedKeyWords as $treatedKeyWord) {
                    $keyWordToAssociate = Keyword::firstOrCreate(["keyword" => $treatedKeyWord]);

                    $keywords[] = $keyWordToAssociate->id;
                }
            }

            if (empty($keywords)) {
                return;
            }

            $keywords = array_map('intval', $keywords);
            $keywords = array_values(array_filter($keywords, fn($id) => $id > 0));
            if (empty($keywords)) {
                return;
            }

            try {
                $model->keywords()->sync($keywords);
            } catch (\Throwable $e) {
                \Log::warning('Keyword sync skipped due to invalid IDs', [
                    'model' => get_class($model),
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    private function removePolymorphicRelations($array, $relationName)
    {
        foreach ($array as $element) {
            try {
                $element["table-class"]::doesntHave($relationName)->delete();
            } catch (\Throwable $e) {
                \Log::warning('Polymorphic cleanup skipped due to lock error', [
                    'relation' => $relationName,
                    'model' => $element["table-class"],
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    private function returnValueIfNotNull($array, $key1, $key2 = null)
    {
        $value = (isset($key2)) ? ($array[$key1][$key2]) ?? null : ($array[$key1]) ?? null;

        return $value;
    }

    private function formatCvDate($date)
    {
        if (!is_array($date)) {
            if (is_string($date) || is_numeric($date)) {
                $dateString = trim((string) $date);

                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateString)) {
                    return $dateString;
                }

                if (preg_match('/^\d{4}$/', $dateString)) {
                    return sprintf('%04d-01-01', (int) $dateString);
                }
            }

            return null;
        }

        $year = $date['year'] ?? null;
        if (!$year) {
            $fallback = $this->normalizeCvScalar($date);
            if ($fallback !== null) {
                $fallbackString = trim((string) $fallback);

                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $fallbackString)) {
                    return $fallbackString;
                }

                if (preg_match('/^\d{4}$/', $fallbackString)) {
                    return sprintf('%04d-01-01', (int) $fallbackString);
                }
            }
        }
        if (!$year) {
            return null;
        }

        $month = $date['month'] ?? 1;
        $day = $date['day'] ?? 1;

        return sprintf('%04d-%02d-%02d', (int) $year, (int) $month, (int) $day);
    }

    private function normalizeCvScalar($value)
    {
        if (is_array($value)) {
            foreach (['value', 'label', 'name', 'code'] as $key) {
                if (array_key_exists($key, $value) && !is_array($value[$key])) {
                    return $value[$key];
                }
            }

            return null;
        }

        return $value;
    }

    private function getNestedValue($data, array $keys)
    {
        $current = $data;

        foreach ($keys as $key) {
            if (!is_array($current) || !array_key_exists($key, $current)) {
                return null;
            }

            $current = $current[$key];
        }

        return $current;
    }

    private function truncateCvString($value, int $max)
    {
        $text = $this->sanitizeCvText($value);
        if ($text === null) {
            return null;
        }

        if (strlen($text) > $max) {
            return substr($text, 0, $max);
        }

        return $text;
    }

    private function sanitizeCvText($value)
    {
        $scalar = $this->normalizeCvScalar($value);
        if ($scalar === null) {
            return null;
        }

        $text = trim((string) $scalar);
        if ($text === '') {
            return null;
        }

        $converted = $text;
        if (function_exists('mb_convert_encoding')) {
            $converted = @mb_convert_encoding($converted, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
        }

        $converted = @iconv('UTF-8', 'UTF-8//IGNORE', $converted);
        if ($converted === false) {
            return null;
        }

        $converted = trim($converted);

        return $converted === '' ? null : $converted;
    }

    private function sanitizeCvUrl($value)
    {
        $text = $this->sanitizeCvText($value);
        if ($text === null) {
            return null;
        }

        $text = trim((string) $text);
        if ($text === '') {
            return null;
        }

        // URLs should be ASCII-safe; strip non-ASCII to avoid encoding errors.
        $ascii = preg_replace('/[^\x20-\x7E]/', '', $text);
        $ascii = trim((string) $ascii);

        return $ascii === '' ? null : $ascii;
    }

    private function sanitizeCvAscii($value)
    {
        $text = $this->sanitizeCvText($value);
        if ($text === null) {
            return null;
        }

        $ascii = preg_replace('/[^\x20-\x7E]/', '', $text);
        $ascii = trim((string) $ascii);

        return $ascii === '' ? null : $ascii;
    }

    private function isUrlField(string $key): bool
    {
        return $key === 'url' || str_ends_with($key, '_url');
    }

    private function normalizeCvInt($value)
    {
        $scalar = $this->normalizeCvScalar($value);
        if ($scalar === null) {
            return null;
        }

        if (is_numeric($scalar)) {
            return $this->clampCvInt((int) $scalar);
        }

        $text = trim((string) $scalar);
        if ($text === '') {
            return null;
        }

        if (preg_match('/\d+/', $text, $m)) {
            return $this->clampCvInt((int) $m[0]);
        }

        $roman = strtoupper(preg_replace('/[^IVXLCDM]/i', '', $text));
        if ($roman !== '') {
            $map = ['I' => 1, 'V' => 5, 'X' => 10, 'L' => 50, 'C' => 100, 'D' => 500, 'M' => 1000];
            $total = 0;
            $prev = 0;

            for ($i = strlen($roman) - 1; $i >= 0; $i--) {
                $value = $map[$roman[$i]] ?? 0;
                if ($value < $prev) {
                    $total -= $value;
                } else {
                    $total += $value;
                    $prev = $value;
                }
            }

            return $total > 0 ? $this->clampCvInt($total) : null;
        }

        return null;
    }

    private function clampCvInt(int $value): int
    {
        if ($value > 2147483647) {
            return 2147483647;
        }

        if ($value < -2147483648) {
            return -2147483648;
        }

        return $value;
    }
}

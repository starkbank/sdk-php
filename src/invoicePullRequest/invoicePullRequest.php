<?php

namespace StarkBank;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;
use StarkBank\Utils\Rest;


class InvoicePullRequest extends Resource
{
    
    public $subscriptionId;
    public $invoiceId;
    public $due;
    public $attemptType;
    public $tags;
    public $externalId;
    public $displayDescription;
    public $status;
    public $bacenId;
    public $installmentId;
    public $created;
    public $updated;

    /**
    # InvoicePullRequest object

    An InvoicePullRequest is a command sent to the payer's bank to trigger the automatic debit of a previously issued Invoice linked to an active InvoicePullSubscription. It confirms the receiver's intent to collect the agreed amount within the current billing cycle and initiates settlement through the Pix infrastructure. Use webhooks to monitor status changes asynchronously.

    ## Parameters (required):
        - subscriptionId [string]: unique id of the InvoicePullSubscription. ex: "5656565656565656"
        - invoiceId [string]: unique id of the Invoice to be pulled. ex: "5155165527080960"
        - due [DateTime or string]: expected date of settlement.
        - attemptType [string]: type of attempt, "default" for the first attempt of a billing cycle or "retry" for a retry. ex: "default"

    ## Parameters (optional):
        - tags [array of strings, default null]: array of strings for tagging

    ## Attributes (return-only):
        - id [string]: unique id returned when the pull request is created. ex: "5656565656565656"
        - externalId [string]: unique external id for the pull request.
        - displayDescription [string]: description presented to the payer.
        - status [string]: current pull request status.
        - installmentId [string]: id of the installment linked to the pull request, if any.
        - created [DateTime]: creation datetime for the pull request.
        - updated [DateTime]: latest update datetime for the pull request.
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->subscriptionId = Checks::checkParam($params, "subscriptionId");
        $this->invoiceId = Checks::checkParam($params, "invoiceId");
        $this->due = Checks::checkDateTime(Checks::checkParam($params, "due"));
        $this->attemptType = Checks::checkParam($params, "attemptType");
        $this->tags = Checks::checkParam($params, "tags");
        $this->externalId = Checks::checkParam($params, "externalId");
        $this->displayDescription = Checks::checkParam($params, "displayDescription");
        $this->status = Checks::checkParam($params, "status");
        $this->bacenId = Checks::checkParam($params, "bacenId");
        $this->installmentId = Checks::checkParam($params, "installmentId");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));

        Checks::checkParams($params);
    }

    /**
    # Create InvoicePullRequests

    Send an array of InvoicePullRequest objects for creation in the Stark Bank API

    ## Parameters (required):
        - requests [array of InvoicePullRequest objects]: array of InvoicePullRequest objects to be created in the API

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - array of InvoicePullRequest objects with updated attributes
     */
    public static function create($invoicePullRequests, $user = null)
    {
        return Rest::post($user, InvoicePullRequest::resource(), $invoicePullRequests);
    }

    /**
    # Retrieve a specific InvoicePullRequest

    Receive a single InvoicePullRequest object previously created in the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - InvoicePullRequest object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, InvoicePullRequest::resource(), $id);
    }

    /**
    # Retrieve InvoicePullRequests

    Receive an enumerator of InvoicePullRequest objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [DateTime or string, default null]: date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null]: date filter for objects created only before specified date. ex: "2020-04-03"
        - status [string, default null]: filter for status of retrieved objects.
        - tags [array of strings, default null]: tags to filter retrieved objects.
        - ids [array of strings, default null]: array of ids to filter retrieved objects.
        - externalIds [array of strings, default null]: array of external ids to filter retrieved objects.
        - invoiceIds [array of strings, default null]: array of invoice ids linked to the desired pull requests.
        - subscriptionIds [array of strings, default null]: array of subscription ids linked to the desired pull requests.
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of InvoicePullRequest objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, InvoicePullRequest::resource(), $options);
    }

    /**
    # Retrieve paged InvoicePullRequests

    Receive a list of up to 100 InvoicePullRequest objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100.
        - (same filters as query: after, before, status, tags, ids, externalIds, invoiceIds, subscriptionIds)
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was set before function call

    ## Return:
        - list of InvoicePullRequest objects with updated attributes
        - cursor to retrieve the next page of InvoicePullRequest objects
     */
    public static function page($options = [], $user = null)
    {
        return Rest::getPage($user, InvoicePullRequest::resource(), $options);
    }

    /**
    # Cancel an InvoicePullRequest entity

    Cancel an InvoicePullRequest entity previously created in the Stark Bank API

    ## Parameters (required):
        - id [string]: InvoicePullRequest unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - canceled InvoicePullRequest object
     */
    public static function cancel($id, $user = null)
    {
        return Rest::deleteId($user, InvoicePullRequest::resource(), $id);
    }

    /**

     */
    private static function resource()
    {
        $request = function ($array) {
            return new InvoicePullRequest($array);
        };
        return [
            "name" => "InvoicePullRequest",
            "maker" => $request,
        ];
    }
}

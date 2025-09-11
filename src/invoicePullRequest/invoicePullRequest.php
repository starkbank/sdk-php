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

     */
    public static function create($invoicePullRequests, $user = null)
    {
        return Rest::post($user, InvoicePullRequest::resource(), $invoicePullRequests);
    }

    /**

     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, InvoicePullRequest::resource(), $id);
    }

    /**

     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, InvoicePullRequest::resource(), $options);
    }

    /**

     */
    public static function page($options = [], $user = null)
    {
        return Rest::getPage($user, InvoicePullRequest::resource(), $options);
    }

    /**

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

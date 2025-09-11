<?php

namespace StarkBank;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;
use StarkBank\Utils\Rest;


class InvoicePullSubscription extends Resource
{
    
    public $start;
    public $interval;
    public $pullMode;
    public $pullRetryLimit;
    public $type;
    public $amount;
    public $amountMinLimit;
    public $displayDescription;
    public $due;
    public $externalId;
    public $referenceCode;
    public $end;
    public $data;
    public $name;
    public $taxId;
    public $tags;
    public $status;
    public $bacenId;
    public $installmentId;
    public $created;
    public $updated;
    public $brcode;

    /**
        
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->start = Checks::checkDateTime(Checks::checkParam($params, "start"));
        $this->interval = Checks::checkParam($params, "interval");
        $this->pullMode = Checks::checkParam($params, "pullMode");
        $this->pullRetryLimit = Checks::checkParam($params, "pullRetryLimit");
        $this->type = Checks::checkParam($params, "type");
        $this->amount = Checks::checkParam($params, "amount");
        $this->amountMinLimit = Checks::checkParam($params, "amountMinLimit");
        $this->displayDescription = Checks::checkParam($params, "displayDescription");
        $this->due = Checks::checkDateTime(Checks::checkParam($params, "due"));
        $this->externalId = Checks::checkParam($params, "externalId");
        $this->referenceCode = Checks::checkParam($params, "referenceCode");
        $this->end = Checks::checkDateTime(Checks::checkParam($params, "end"));
        $this->data = Checks::checkParam($params, "data");
        $this->name = Checks::checkParam($params, "name");
        $this->taxId = Checks::checkParam($params, "taxId");
        $this->tags = Checks::checkParam($params, "tags");
        $this->status = Checks::checkParam($params, "status");
        $this->bacenId = Checks::checkParam($params, "bacenId");
        $this->installmentId = Checks::checkParam($params, "installmentId");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));
        $this->brcode = Checks::checkParam($params, "brcode");

        Checks::checkParams($params);
    }

    /**
  
     */
    public static function create($invoicePullRequests, $user = null)
    {
        return Rest::post($user, InvoicePullSubscription::resource(), $invoicePullRequests);
    }

    /**

     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, InvoicePullSubscription::resource(), $id);
    }

    /**
   
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, InvoicePullSubscription::resource(), $options);
    }

    /**
    
     */
    public static function page($options = [], $user = null)
    {
        return Rest::getPage($user, InvoicePullSubscription::resource(), $options);
    }

    /**

     */
    public static function cancel($id, $user = null)
    {
        return Rest::deleteId($user, InvoicePullSubscription::resource(), $id);
    }

    /**
  
     */
    private static function resource()
    {
        $subscription = function ($array) {
            return new InvoicePullSubscription($array);
        };
        return [
            "name" => "InvoicePullSubscription",
            "maker" => $subscription,
        ];
    }
}

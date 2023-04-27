<?php

namespace StarkBank;
use StarkBank\Utils\Rest;
use StarkCore\Utils\Checks;
use StarkCore\Utils\Resource;
use StarkCore\Utils\StarkDate;


class CorporateInvoice extends Resource
{

    public $amount;
    public $tags;
    public $name;
    public $taxId;
    public $brcode;
    public $due;
    public $link;
    public $status;
    public $corporateTransactionId;
    public $created;
    public $updated;

    /**
    # CorporateInvoice object

    The CorporateInvoice objects created in your Workspace load your Corporate balance when paid.

    ## Parameters (required):
        - amount [integer]: CorporateInvoice value in cents. ex: 1234 (= R$ 12.34)

    ## Parameters (optional):
        - tags [array of strings, default null]: array of strings for tagging. ex: ["travel", "food"]

    ## Attributes (return-only):
        - id [string]: unique id returned when CorporateInvoice is created. ex: "5656565656565656"
        - name [string, default sub-issuer name]: payer name. ex: "Iron Bank S.A."
        - taxId [string, default sub-issuer taxId]: payer tax ID (CPF or CNPJ) with or without formatting. ex: "01234567890" or "20.018.183/0001-80"
        - brcode [string]: BR Code for the Invoice payment. ex: "00020101021226930014br.gov.bcb.pix2571brcode-h.development.starkinfra.com/v2/d7f6546e194d4c64a153e8f79f1c41ac5204000053039865802BR5925Stark Bank S.A. - Institu6009Sao Paulo62070503***63042109"
        - due [DateTime]: Invoice due datetime for the CorporateInvoice.
        - link [string]: public Invoice webpage URL. ex: "https://starkbank-card-issuer.development.starkbank.com/invoicelink/d7f6546e194d4c64a153e8f79f1c41ac"
        - status [string]: current CorporateInvoice status. ex: "created", "expired", "overdue", "paid"
        - corporateTransactionId [string]: ledger transaction ids linked to this CorporateInvoice. ex: "corporate-invoice/5656565656565656"
        - created [DateTime]: creation datetime for the CorporateInvoice. 
        - updated [DateTime]: latest update datetime for the CorporateInvoice. 
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->amount = Checks::checkParam($params, "amount");
        $this->taxId = Checks::checkParam($params, "taxId");
        $this->name = Checks::checkParam($params, "name");
        $this->tags = Checks::checkParam($params, "tags");
        $this->brcode = Checks::checkParam($params, "brcode");
        $this->due = Checks::checkDateTime(Checks::checkParam($params, "due"));
        $this->link = Checks::checkParam($params, "link");
        $this->status = Checks::checkParam($params, "status");
        $this->corporateTransactionId = Checks::checkParam($params, "corporateTransactionId");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));

        Checks::checkParams($params);
    }

    /**
    # Create an CorporateInvoice

    Send an CorporateInvoice objects for creation in the Stark Bank API

    ## Parameters (required):
        - invoice [CorporateInvoice object]: CorporateInvoice object to be created in the API

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - CorporateInvoice object with updated attributes
     */ 
    public static function create($invoices, $user = null)
    {
        return Rest::postSingle($user, CorporateInvoice::resource(), $invoices);
    }

    /**
    # Retrieve a specific CorporateInvoice

    Receive a single CorporateInvoice object previously created in the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - CorporateInvoice object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, CorporateInvoice::resource(), $id);
    }

    /**
    # Retrieve CorporateInvoices

    Receive an enumerator of CorporateInvoice objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [Date or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [Date or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - status [string, default null]: filter for status of retrieved objects. ex: "created", "expired", "overdue", "paid"
        - tags [array of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call

    ## Return:
        - enumerator of CorporateInvoice objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getList($user, CorporateInvoice::resource(), $options);
    }

    /**
    # Retrieve paged Invoices

    Receive a list of up to 100 Invoice objects previously created in the Stark Bank API and the cursor to the next page.
    Use this function instead of query if you want to manually page your requests.

    ## Parameters (optional):
        - cursor [string, default null]: cursor returned on the previous page function call
        - limit [integer, default 100]: maximum number of objects to be retrieved. It must be an integer between 1 and 100. ex: 50
        - after [Date or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [Date or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - status [string, default null]: filter for status of retrieved objects. ex: "created", "expired", "overdue", "paid"
        - tags [array of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - user [Organization/Project object, default null]: Organization or Project object. Not necessary if StarkBank\Settings::setUser() was used before function call
    
    ## Return:
        - list of CorporateInvoice objects with updated attributes
        - cursor to retrieve the next page of CorporateInvoice objects
     */
    public static function page($options = [], $user = null)
    {
        $options["after"] = new StarkDate(Checks::checkParam($options, "after"));
        $options["before"] = new StarkDate(Checks::checkParam($options, "before"));
        return Rest::getPage($user, CorporateInvoice::resource(), $options);
    }

    private static function resource()
    {
        $invoice = function ($array) {
            return new CorporateInvoice($array);
        };
        return [
            "name" => "CorporateInvoice",
            "maker" => $invoice,
        ];
    }
}

<?php

namespace StarkBank;
use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use StarkBank\Utils\Rest;


class Invoice extends Resource
{
    /**
    # Invoice object

    When you initialize a Invoice, the entity will not be automatically
    sent to the Stark Bank API. The "create" function sends the objects
    to the Stark Bank API and returns the array of created objects.

    ## Parameters (required):
        - amount [integer]: Invoice value in cents. Minimum = 0 (R$0,00). ex: 1234 (= R$ 12.34)
        - due [DateTime or string, default today + 2 days]: Invoice due date in UTC ISO format. ex: "2020-11-25T17:59:26.249976+00:00"
        - taxId [string]: payer tax ID (CPF or CNPJ) with or without formatting. ex: "01234567890" or "20.018.183/0001-80"
        - name [string]: payer name. ex: "Iron Bank S.A."

    ## Parameters (optional):
        - expiration [integer, default null]: time interval in seconds between due date and expiration date. ex 123456789
        - fine [float, default 0.0]: Invoice fine for overdue payment in %. ex: 2.5
        - interest [float, default 0.0]: Invoice monthly interest for overdue payment in %. ex: 5.2
        - discounts [array of dictionaries, default null]: array of dictionaries with "percentage":float and "due":DateTime or string pairs
        - tags [array of strings, default null]: array of strings for tagging
        - descriptions [array of dictionaries, default null]: array of dictionaries with "key":string and (optional) "value":string pairs

    ## Attributes (return-only):
        - nominalAmount [integer]: Invoice emission value in cents (will change if invoice is updated, but not if it"s paid). ex: 400000
        - fineAmount [integer]: Invoice fine value calculated over nominalAmount. ex: 20000
        - interestAmount [integer]: Invoice interest value calculated over nominalAmount. ex: 10000
        - discountAmount [integer]: Invoice discount value calculated over nominalAmount. ex: 3000
        - id [string, default null]: unique id returned when Invoice is created. ex: "5656565656565656"
        - brcode [string, default null]: BR Code for the Invoice payment. ex: "00020101021226800014br.gov.bcb.pix2558invoice.starkbank.com/f5333103-3279-4db2-8389-5efe335ba93d5204000053039865802BR5913Arya Stark6009Sao Paulo6220051656565656565656566304A9A0"
        - status [string, default null]: current Invoice status. ex: "created", "paid", "canceled" or "overdue"
        - created [string, default null]: creation datetime for the Invoice. ex: "2020-03-10 10:30:00.000"
        - updated [string, default null]: creation datetime for the Invoice. ex: "2020-03-10 10:30:00.000"
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->amount = Checks::checkParam($params, "amount");
        $this->due = Checks::checkDateTime(Checks::checkParam($params, "due"));
        $this->taxId = Checks::checkParam($params, "taxId");
        $this->name = Checks::checkParam($params, "name");
        $this->expiration = Checks::checkParam($params, "expiration");
        $this->fine = Checks::checkParam($params, "fine");
        $this->interest = Checks::checkParam($params, "interest");
        $this->discounts = Checks::checkParam($params, "discounts");
        $this->tags = Checks::checkParam($params, "tags");
        $this->descriptions = Checks::checkParam($params, "descriptions");
        $this->nominalAmount = Checks::checkParam($params, "nominalAmount");
        $this->fineAmount = Checks::checkParam($params, "fineAmount");
        $this->interestAmount = Checks::checkParam($params, "interestAmount");
        $this->discountAmount = Checks::checkParam($params, "discountAmount");
        $this->brcode = Checks::checkParam($params, "brcode");
        $this->status = Checks::checkParam($params, "status");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));

        Checks::checkParams($params);
    }

    /**
    # Create Invoices

    Send an array of Invoice objects for creation in the Stark Bank API

    ## Parameters (required):
        - invoices [array of Invoice objects]: array of Invoice objects to be created in the API

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - array of Invoice objects with updated attributes
     */
    public static function create($invoices, $user = null)
    {
        return Rest::post($user, Invoice::resource(), $invoices);
    }

    /**
    # Retrieve a specific Invoice

    Receive a single Invoice object previously created in the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - Invoice object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, Invoice::resource(), $id);
    }

    /**
    # Retrieve Invoices

    Receive an enumerator of Invoice objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [DateTime or string, default null] date filter for objects created only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null] date filter for objects created only before specified date. ex: "2020-04-03"
        - status [string, default null]: filter for status of retrieved objects. ex: "created", "paid", "canceled" or "overdue"
        - tags [array of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - ids [array of strings, default null]: array of ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - user [Project object, default null]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - enumerator of Invoice objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = Checks::checkDateTime(Checks::checkParam($options, "after"));
        $options["before"] = Checks::checkDateTime(Checks::checkParam($options, "before"));
        return Rest::getList($user, Invoice::resource(), $options);
    }

    /**
    # Update notification Invoice entity

    Update notification Invoice by passing id.

    ## Parameters (required):
        - id [array of strings]: Invoice unique ids. ex: "5656565656565656"
        - status [string]: If the Invoice hasn't been paid yet, you may cancel it by passing "canceled" in the status
        - amount [string]: If the Invoice hasn't been paid yet, you may update its amount by passing the desired amount integer
        - due [string, default today + 2 days]: Invoice due date in UTC ISO format. ex: "2020-11-25T17:59:26.249976+00:00"
        - expiration [integer, default null]: time interval in seconds between due date and expiration date. ex 123456789
        - user [Project object]: Project object. Not necessary if starkbank.user was set before function call

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - target Invoice with updated attributes
     */
    public static function update($id, $options = [], $user = null)
    {
        return Rest::patchId($user, Invoice::resource(), $id, $options);
    }

    private static function resource()
    {
        $invoice = function ($array) {
            return new Invoice($array);
        };
        return [
            "name" => "Invoice",
            "maker" => $invoice,
        ];
    }
}

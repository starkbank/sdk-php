<?php

namespace StarkBank;
use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use StarkBank\Utils\Rest;


class Transfer extends Resource
{
    /**
    # Transfer object

    When you initialize a Transfer, the entity will not be automatically
    created in the Stark Bank API. The 'create' function sends the objects
    to the Stark Bank API and returns the list of created objects.

    ## Parameters (required):
        - amount [integer]: amount in cents to be transferred. ex: 1234 (= R$ 12.34)
        - name [string]: receiver full name. ex: "Anthony Edward Stark"
        - taxId [string]: receiver tax ID (CPF or CNPJ) with or without formatting. ex: "01234567890" or "20.018.183/0001-80"
        - bankCode [string]: 1 to 3 digits of the receiver bank institution in Brazil. ex: "200" or "341"
        - branchCode [string]: receiver bank account branch. Use '-' in case there is a verifier digit. ex: "1357-9"
        - accountNumber [string]: Receiver Bank Account number. Use '-' before the verifier digit. ex: "876543-2"

    ## Parameters (optional):
        - tags [list of strings]: list of strings for reference when searching for transfers. ex: ["employees", "monthly"]
        - scheduled [string, default now]: datetime when the transfer will be processed. May be pushed to next business day if necessary.

    ## Attributes (return-only):
        - id [string, default null]: unique id returned when Transfer is created. ex: "5656565656565656"
        - fee [integer, default null]: fee charged when transfer is created. ex: 200 (= R$ 2.00)
        - status [string, default null]: current transfer status. ex: "success" or "failed"
        - transactionIds [list of strings, default null]: ledger transaction ids linked to this transfer (if there are two, second is the chargeback). ex: ["19827356981273"]
        - created [DateTime, default null]: creation datetime for the transfer.
        - updated [DateTime, default null]: latest update datetime for the transfer.
     */
    function __construct(array $params)
    {
        parent::__construct($params);

        $this->amount = Checks::checkParam($params, "amount");
        $this->name = Checks::checkParam($params, "name");
        $this->taxId = Checks::checkParam($params, "taxId");
        $this->bankCode = Checks::checkParam($params, "bankCode");
        $this->branchCode = Checks::checkParam($params, "branchCode");
        $this->accountNumber = Checks::checkParam($params, "accountNumber");
        $this->scheduled = Checks::checkDateTime(Checks::checkParam($params, "scheduled"));
        $this->tags = Checks::checkParam($params, "tags");
        $this->fee = Checks::checkParam($params, "fee");
        $this->status = Checks::checkParam($params, "status");
        $this->transactionIds = Checks::checkParam($params, "transactionIds");
        $this->created = Checks::checkDateTime(Checks::checkParam($params, "created"));
        $this->updated = Checks::checkDateTime(Checks::checkParam($params, "updated"));

        Checks::checkParams($params);
    }

    /**
    # Create Transfers

    Send a list of Transfer objects for creation in the Stark Bank API

    ## Parameters (required):
        - transfers [list of Transfer objects]: list of Transfer objects to be created in the API

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - list of Transfer objects with updated attributes
     */
    public static function create($transfers, $user = null)
    {
        return Rest::post($user, Transfer::resource(), $transfers);
    }

    /**
    # Retrieve a specific Transfer

    Receive a single Transfer object previously created in the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - Transfer object with updated attributes
     */
    public static function get($id, $user = null)
    {
        return Rest::getId($user, Transfer::resource(), $id);
    }

    /**
    # Delete a Transfer entity

    Delete a Transfer entity previously created in the Stark Bank API

    ## Parameters (required):
        - id [string]: Transfer unique id. ex: "5656565656565656"
    
    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call
    
    ## Return:
        - deleted Transfer object
     */
    public static function delete($id, $user = null)
    {
        return Rest::deleteId($user, Transfer::resource(), $id);
    }

    /**
    # Retrieve a specific Transfer pdf file

    Receive a single Transfer pdf receipt file generated in the Stark Bank API by passing its id.
    Only valid for transfers with "processing" and "success" status.

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - Transfer pdf file
     */
    public static function pdf($id, $user = null)
    {
        return Rest::getPdf($user, Transfer::resource(), $id);
    }

    /**
    # Retrieve Transfers

    Receive a enumerator of Transfer objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - after [DateTime or string, default null]: date filter for objects created or updated only after specified date. ex: "2020-04-03"
        - before [DateTime or string, default null]: date filter for objects created or updated only before specified date. ex: "2020-04-03"
        - transactionIds [list of strings, default null]: list of transaction IDs linked to the desired transfers. ex: ["5656565656565656", "4545454545454545"]
        - status [string, default null]: filter for status of retrieved objects. ex: "paid" or "registered"
        - taxId [string, default null]: filter for transfers sent to the specified tax ID. ex: "012.345.678-90"
        - sort [string, default "-created"]: sort order considered in response. Valid options are 'created', '-created', 'updated' or '-updated'.
        - tags [list of strings, default null]: tags to filter retrieved objects. ex: ["tony", "stark"]
        - user [Project object, default null]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - enumerator of Transfer objects with updated attributes
     */
    public static function query($options = [], $user = null)
    {
        $options["after"] = Checks::checkDateTime(Checks::checkParam($options, "after"));
        $options["before"] = Checks::checkDateTime(Checks::checkParam($options, "before"));
        return Rest::getList($user, Transfer::resource(), $options);
    }

    private static function resource()
    {
        $transfer = function ($array) {
            return new Transfer($array);
        };
        return [
            "name" => "Transfer",
            "maker" => $transfer,
        ];
    }
}

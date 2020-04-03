<?php

namespace StarkBank;
use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use StarkBank\Utils\Rest;


class Transfer extends Resource
{
    /**
    Transfer object

    When you initialize a Transfer, the entity will not be automatically
    created in the Stark Bank API. The 'create' function sends the objects
    to the Stark Bank API and returns the list of created objects.

    Parameters (required):
        amount [integer]: amount in cents to be transferred. ex: 1234 (= R$ 12.34)
        name [string]: receiver full name. ex: "Anthony Edward Stark"
        tax_id [string]: receiver tax ID (CPF or CNPJ) with or without formatting. ex: "01234567890" or "20.018.183/0001-80"
        bank_code [string]: receiver 1 to 3 digits of the bank institution in Brazil. ex: "200" or "341"
        branch_code [string]: receiver bank account branch. Use '-' in case there is a verifier digit. ex: "1357-9"
        account_number [string]: Receiver Bank Account number. Use '-' before the verifier digit. ex: "876543-2"
    Parameters (optional):
        tags [list of strings]: list of strings for reference when searching for transfers. ex: ["employees", "monthly"]
    Attributes (return-only):
        id [string, default None]: unique id returned when Transfer is created. ex: "5656565656565656"
        fee [integer, default None]: fee charged when transfer is created. ex: 200 (= R$ 2.00)
        status [string, default None]: current transfer status. ex: "registered" or "paid"
        transaction_ids [list of strings, default None]: ledger transaction ids linked to this transfer (if there are two, second is the chargeback). ex: ["19827356981273"]
        created [datetime.datetime, default None]: creation datetime for the transfer. ex: datetime.datetime(2020, 3, 10, 10, 30, 0, 0)
        updated [datetime.datetime, default None]: latest update datetime for the transfer. ex: datetime.datetime(2020, 3, 10, 10, 30, 0, 0)
     */
    function __construct(array $params)
    {
        parent::__construct($params["id"]);
        unset($params["id"]);
        $this->amount = $params["amount"];
        unset($params["amount"]);
        $this->name = $params["name"];
        unset($params["name"]);
        $this->taxId = $params["taxId"];
        unset($params["taxId"]);
        $this->bankCode = $params["bankCode"];
        unset($params["bankCode"]);
        $this->branchCode = $params["branchCode"];
        unset($params["branchCode"]);
        $this->accountNumber = $params["accountNumber"];
        unset($params["accountNumber"]);
        $this->tags = $params["tags"];
        unset($params["tags"]);
        $this->fee = $params["fee"];
        unset($params["fee"]);
        $this->status = $params["status"];
        unset($params["status"]);
        $this->transactionIds = $params["transactionIds"];
        unset($params["transactionIds"]);
        $this->created = Checks::checkDateTime($params["created"]);
        unset($params["created"]);
        $this->updated = Checks::checkDateTime($params["updated"]);
        unset($params["updated"]);

        Checks::checkParams($params);
    }

    /**
    Create Transfers

    Send a list of Transfer objects for creation in the Stark Bank API

    Parameters (required):
        transfers [list of Transfer objects]: list of Transfer objects to be created in the API
    Parameters (optional):
        user [Project object]: Project object. Not necessary if starkbank.user was set before function call
    Return:
        list of Transfer objects with updated attributes
     */
    public function create($user, $transfers)
    {
        return Rest::post($user, Transfer::resource(), $transfers);
    }

    /**
    Retrieve a specific Transfer

    Receive a single Transfer object previously created in the Stark Bank API by passing its id

    Parameters (required):
        id [string]: object unique id. ex: "5656565656565656"
    Parameters (optional):
        user [Project object]: Project object. Not necessary if starkbank.user was set before function call
    Return:
        Transfer object with updated attributes
     */
    public function get($user, $id)
    {
        return Rest::getId($user, Transfer::resource(), $id);
    }

    /**
    Retrieve a specific Transfer pdf file

    Receive a single Transfer pdf receipt file generated in the Stark Bank API by passing its id.
    Only valid for transfers with "processing" and "success" status.

    Parameters (required):
        id [string]: object unique id. ex: "5656565656565656"
    Parameters (optional):
        user [Project object]: Project object. Not necessary if starkbank.user was set before function call
    Return:
        Transfer pdf file
     */
    public function pdf($user, $id)
    {
        return Rest::getPdf($user, Transfer::resource(), $id);
    }

    /**
    Retrieve Transfers

    Receive a generator of Transfer objects previously created in the Stark Bank API

    Parameters (optional):
        limit [integer, default None]: maximum number of objects to be retrieved. Unlimited if None. ex: 35
        status [string, default None]: filter for status of retrieved objects. ex: "paid" or "registered"
        tags [list of strings, default None]: tags to filter retrieved objects. ex: ["tony", "stark"]
        transaction_ids [list of strings, default None]: list of Transaction ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        after [datetime.date, default None]: date filter for objects created only after specified date. ex: datetime.date(2020, 3, 10)
        before [datetime.date, default None]: date filter for objects only before specified date. ex: datetime.date(2020, 3, 10)
        sort [string, default "-created"]: sort order considered in response. Valid options are 'created', '-created', 'updated' or '-updated'.
        user [Project object, default None]: Project object. Not necessary if starkbank.user was set before function call
    Return:
        generator of Transfer objects with updated attributes
     */
    public function query($user, $options = [])
    {
        $options["after"] = Checks::checkDateTime($options["after"]);
        $options["before"] = Checks::checkDateTime($options["before"]);
        return Rest::getList($user, Transfer::resource(), $options);
    }

    private function resource()
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

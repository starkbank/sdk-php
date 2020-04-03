<?php

namespace StarkBank;

use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use StarkBank\Utils\Rest;


class Transaction extends Resource
{
    /**
    # Transaction object

    A Transaction is a transfer of funds between workspaces inside Stark Bank.
    Transactions created by the user are only for internal transactions.
    Other operations (such as transfer or charge-payment) will automatically
    create a transaction for the user which can be retrieved for the statement.
    When you initialize a Transaction, the entity will not be automatically
    created in the Stark Bank API. The 'create' function sends the objects
    to the Stark Bank API and returns the list of created objects.

    ## Parameters (required):
        - amount [integer]: amount in cents to be transferred. ex: 1234 (= R$ 12.34)
        - description [string]: text to be displayed in the receiver and the sender statements (Min. 10 characters). ex: "funds redistribution"
        - external_id [string]: unique id, generated by user, to avoid duplicated transactions. ex: "transaction ABC 2020-03-30"
        - received_id [string]: unique id of the receiving workspace. ex: "5656565656565656"

    ## Parameters (optional):
        - tags [list of strings]: list of strings for reference when searching transactions (may be empty). ex: ["abc", "test"]

    ## Attributes (return-only):
        - source [string, default null]: locator of the entity that generated the transaction. ex: "charge/1827351876292", "transfer/92873912873/chargeback"
        - id [string, default null]: unique id returned when Transaction is created. ex: "7656565656565656"
        - fee [integer, default null]: fee charged when transfer is created. ex: 200 (= R$ 2.00)
        - created [DateTime, default null]: creation datetime for the boleto.
     */
    function __construct(array $params)
    {
        parent::__construct($params["id"]);
        unset($params["id"]);
        $this->amount = $params["amount"];
        unset($params["amount"]);
        $this->description = $params["description"];
        unset($params["description"]);
        $this->externalId = $params["externalId"];
        unset($params["externalId"]);
        $this->receiverId = $params["receiverId"];
        unset($params["receiverId"]);
        $this->tags = $params["tags"];
        unset($params["tags"]);
        $this->fee = $params["fee"];
        unset($params["fee"]);
        $this->created = Checks::checkDateTime($params["created"]);
        unset($params["created"]);
        $this->source = $params["source"];
        unset($params["source"]);

        Checks::checkParams($params);
    }

    /**
    # Create Transactions

    Send a list of Transaction objects for creation in the Stark Bank API

    ## Parameters (required):
        - transactions [list of Transaction objects]: list of Transaction objects to be created in the API

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - list of Transaction objects with updated attributes
     */
    public function create($transactions, $user = null)
    {
        return Rest::post($user, Transaction::resource(), $transactions);
    }

    /**
    # Retrieve a specific Transaction

    Receive a single Transaction object previously created in the Stark Bank API by passing its id

    ## Parameters (required):
        - id [string]: object unique id. ex: "5656565656565656"

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call
    
    ## Return:
        - Transaction object with updated attributes
     */
    public function get($id, $user = null)
    {
        return Rest::getId($user, Transaction::resource(), $id);
    }

    /**
    # Retrieve Transactions

    Receive a generator of Transaction objects previously created in the Stark Bank API

    ## Parameters (optional):
        - limit [integer, default null]: maximum number of objects to be retrieved. Unlimited if null. ex: 35
        - external_ids [list of strings, default null]: list of external ids to filter retrieved objects. ex: ["5656565656565656", "4545454545454545"]
        - after [DateTime, default null] date filter for objects created only after specified date.
        - before [DateTime, default null] date filter for objects created only before specified date.
        - user [Project object, default null]: Project object. Not necessary if StarkBank\User.setDefaut() was set before function call

    ## Return:
        - generator of Transaction objects with updated attributes
     */
    public function query($options = [], $user = null)
    {
        $options["after"] = Checks::checkDateTime($options["after"]);
        $options["before"] = Checks::checkDateTime($options["before"]);
        return Rest::getList($user, Transaction::resource(), $options);
    }

    private function resource()
    {
        $transaction = function ($array) {
            return new Transaction($array);
        };
        return [
            "name" => "Transaction",
            "maker" => $transaction,
        ];
    }
}

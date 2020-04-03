<?php

namespace StarkBank;
use StarkBank\Utils\Resource;
use StarkBank\Utils\Checks;
use StarkBank\Utils\Rest;


class Balance extends Resource
{
    /**
    # Balance object

    The Balance object displays the current balance of the workspace,
    which is the result of the sum of all transactions within this
    workspace. The balance is never generated by the user, but it
    can be retrieved to see the information available.

    ## Attributes (return-only):
        - id [string, default None]: unique id returned when Boleto is created. ex: "5656565656565656"
        - amount [integer, default None]: current balance amount of the workspace in cents. ex: 200 (= R$ 2.00)
        - currency [string, default None]: currency of the current workspace. Expect others to be added eventually. ex: "BRL"
        - updated [datetime.datetime, default None]: update datetime for the balance. ex: datetime.datetime(2020, 3, 10, 10, 30, 0, 0)
     */
    function __construct(array $params)
    {
        parent::__construct($params["id"]);
        unset($params["id"]);
        $this->amount = $params["amount"];
        unset($params["amount"]);
        $this->currency = $params["currency"];
        unset($params["currency"]);
        $this->updated = Checks::checkDateTime($params["updated"]);
        unset($params["updated"]);

        Checks::checkParams($params);
    }

    /**
    # Retrieve the Balance object

    Receive the Balance object linked to your workspace in the Stark Bank API

    ## Parameters (optional):
        - user [Project object]: Project object. Not necessary if starkbank.user was set before function call

    ## Return:
        - Balance object with updated attributes
     */
    public function get($user)
    {
        return Rest::getList($user, Balance::resource())->current();
    }

    private function resource()
    {
        $balance = function ($array) {
            return new Balance($array);
        };
        return [
            "name" => "Balance",
            "maker" => $balance,
        ];
    }
}

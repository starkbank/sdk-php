<?php

namespace StarkBank\Invoice;
use StarkCore\Utils\Checks;
use StarkCore\Utils\SubResource;


class Payment extends SubResource
{

  public $name;
  public $taxId;
  public $bankCode;
  public $branchCode;
  public $accountNumber;
  public $accountType;
  public $amount;
  public $endToEndId;
  public $method;

  /**
    # Invoice.Payment object

    When an Invoice is paid, its Payment sub-resource will become available.
    It carries all the available information about the invoice payment.

    ## Attributes (return-only):
        - amount [integer]: amount in cents that was paid. ex: 1234 (= R$ 12.34)
        - name [string]: payer full name. ex: "Anthony Edward Stark"
        - taxId [string]: payer tax ID (CPF or CNPJ). ex: "20.018.183/0001-80"
        - bank_code [string]: code of the payer bank institution in Brazil. ex: "20018183"
        - branch_code [string]: payer bank account branch. ex: "1357-9"
        - account_number [string]: payer bank account number. ex: "876543-2"
        - account_type [string]: payer bank account type. ex: "checking", "savings", "salary" or "payment"
        - end_to_end_id [string]: central bank's unique transaction ID. ex: "E79457883202101262140HHX553UPqeq"
        - method [string]: payment method that was used. ex: "pix"
   */
  function __construct(array $params)
  {
    $this->name = Checks::checkParam($params, "name");
    $this->taxId = Checks::checkParam($params, "taxId");
    $this->bankCode = Checks::checkParam($params, "bankCode");
    $this->branchCode = Checks::checkParam($params, "branchCode");
    $this->accountNumber = Checks::checkParam($params, "accountNumber");
    $this->accountType = Checks::checkParam($params, "accountType");
    $this->amount = Checks::checkParam($params, "amount");
    $this->endToEndId = Checks::checkParam($params, "endToEndId");
    $this->method = Checks::checkParam($params, "method");

    Checks::checkParams($params);
  }

  private static function subResource()
  {
    $payment = function ($array) {
      return new Payment($array);
    };
    return [
      "name" => "Payment",
      "maker" => $payment,
    ];
  }
}

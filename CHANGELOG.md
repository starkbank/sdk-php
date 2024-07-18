# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)
and this project adheres to the following versioning pattern:

Given a version number MAJOR.MINOR.PATCH, increment:

- MAJOR version when the **API** version is incremented. This may include backwards incompatible changes;
- MINOR version when **breaking changes** are introduced OR **new functionalities** are added in a backwards compatible manner;
- PATCH version when backwards compatible bug **fixes** are implemented.


## [Unreleased]

## [2.17.0] - 2024-07-18
### Changed
- core version
### Added
- request methodss

## [2.16.0] - 2024-03-07
### Added
- Split resource
- SplitReceiver resource
- SplitProfile resource
- update starkCore version
- Split to Invoice resource
- Split to Boleto resource

## [2.15.0] - 2024-01-22
### Added
- update function to Deposit resource

## [2.14.0] - 2023-09-18
### Removed 
- accountCreated, created, owned attributes to DictKey resource
- accountNumber, branchCode attributes to PaymentPreview resource
### Changed
- accountNumber, branchCode attributes to DictKey resource
### Fixed 
- accountType docstring attribute to DictKey resource

## [2.13.0] - 2023-05-03
### Added
- rules attribute to Invoice resource
- Invoice.Rule sub-resource
- schedule attribute to CorporateRules resource
- purposes attribute to CorporateRules resource
- description attribute to Corporate Purchase Log

## [2.12.0] - 2023-04-27
### Added
- CorporateBalance resource
- CorporateCard resource
- CorporateHolder resource
- CorporateInvoice resource
- CorporatePurchase resource
- CorporateRule resource
- CorporateTransaction resource
- CorporateWithdrawal resource
- CardMethod sub-resource
- MerchantCategory sub-resource
- MerchantCountry sub-resource

## [2.11.0] - 2023-03-22
### Added
- metadata attribute to Transfer resource
- workspaceId attribute to Boleto resource
- updated attribute to BoletoHolmes\Log resource
- transactionIds attribute to DarfPayment, TaxPayment resource
- transactionIds, type and updated attribute to UtilityPayment resource
- status, organizationId, pictureUrl and created attribute to Workspace resource
- pictureUrl attribute to DynamicBrcode resource
- rules attribute to BrcodePayment resource
- BrcodePayment\Rule sub-resource
- rules attribute to Transfer resource
- Transfer\Rule sub-resource
### Changed
- amount attribute to parameter on BoletoPayment resource
### Removed
- deprecated BrcodePreview resource

## [2.10.0] - 2023-01-16
### Added
- DynamicBrcode resource

## [2.9.2] - 2022-10-27
### Fixed
- ecdsa dependency missing classmap

## [2.9.1] - 2022-10-26
### Fixed
- repeated autoload requirement

## [2.9.0] - 2022-10-25
### Changed
- internal structure to use starkcore as a dependency.

## [2.8.0] - 2021-09-04
### Added
- Support for scheduled invoices, which will display discounts, fine, interest, etc. on the users banking interface when dates are used instead of datetimes
- PaymentPreview resource to preview multiple types of payments before confirmation: BrcodePreview, BoletoPreview, UtilityPreview and TaxPreview

## [2.7.0] - 2021-07-13
### Added
- "payment" account type for Pix related resources
- Transfer->description property to allow control over corresponding Transaction descriptions
- Event->workspaceId property to multiple Workspace Webhook identification
- Workspace::update() to allow parameter updates
- Base exception class
- Missing parameters to Boleto, BrcodePayment, Deposit, DictKey and Invoice resources
- Event->Attempt sub-resource to allow retrieval of information on failed webhook event delivery attempts
- pdf method for retrieving PDF receipts from reversed invoice logs
- page functions as a manual-pagination alternative to queries 
- Institution resource to allow query of institutions recognized by the Brazilian Central Bank for Pix and TED transactions
- TaxPayment resource
- DarfPayment resource

## [2.6.0] - 2021-06-09
### Added
- Invoice.link property to allow easy access to invoice webpage

## [2.5.2] - 2021-06-06
### Fixed
- Imports on main file to avoid conflicts with user files

## [2.5.1] - 2021-06-01
### Fixed
- Ignored Invoice.discounts parameter

## [2.5.0] - 2021-05-17
### Added
- Invoice.Payment sub-resource to allow retrieval of invoice payment information

## [2.4.1] - 2021-04-06
### Fixed
- Uncaught Exception when passing datetimes as strings

## [2.4.0] - 2021-01-21
### Added
- Transfer->accountType property to allow "checking", "salary" or "savings" account specification
- Transfer->externalId property to allow users to take control over duplication filters

## [2.3.0] - 2021-01-20
### Added
- Organization user
- Add Organization User

## [2.2.1] - 2020-12-07
### Fixed
- UTF8 encoding issues

## [2.2.0] - 2020-11-19
### Added
- Invoice resource to load your account with dynamic QR Codes
- DictKey resource to get Pix key's parameters
- Deposit resource to receive transfers passively
- Pix support in Transfer resource
- BrcodePayment support to pay static and dynamic Pix QR Codes

## [2.1.0] - 2020-10-28
### Added
- BoletoHolmes to investigate boleto status according to CIP

## [2.0.0] - 2020-10-19
### Added
- ids parameter to transaction.query
- ids parameter to transfer.query
- PaymentRequest resource to pass payments through manual approval flow

## [0.6.0] - 2020-08-20
### Added
- transfer->scheduled parameter to allow Transfer scheduling
- StarkBank\Transfer::delete to cancel scheduled Transfers
- Transaction query by tags

## [0.5.1] - 2020-07-07
### Fixed
- HTTP 411 response on PHP Ubuntu

## [0.5.0] - 2020-06-05
### Added
- Travis CI integration
- Boleto PDF layout option
- Global error language setting
- Transfer query taxId parameter
### Changed
- StarkBank\User::setDefault() to StarkBank\Settings::setUser()
### Fixed
- Null JSON warning
### Removed
- PHP 7.0 compatibility

### Change
- Test user credentials to environment variable instead of hard-code

## [0.4.1] - 2020-05-15
### Added
- Support for PHP 7.0 & 7.1

## [0.4.0] - 2020-05-12
### Added
- "receiver_name" & "receiver_tax_id" properties to Boleto entities

## [0.3.1] - 2020-05-04
### Fixed
- Docstrings

## [0.3.0] - 2020-05-04
### Added
- Support for direct arrays in create methods
- "balance" property to Transaction entities

## [0.2.0] - 2020-04-29
### Added
- "discounts" property to Boleto entities
- Support for PHP 7.2
### Changed
- Internal folder structure
- Constructor internal pattern
### Fixed
- Docstrings
- Boleto payment test case

## [0.1.1] - 2020-04-18
### Changed
- Internal file names

## [0.1.0] - 2020-04-17
### Added
- Full Stark Bank API v2 compatibility

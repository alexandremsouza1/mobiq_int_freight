<?php


return [
  'corporate_name' => env('CORPORATE_NAME', ''),
  'fantasy_name' => env('FANTASY_NAME', ''),
  'cnpj' => env('CNPJ', ''),
  'address' => env('ADDRESS', ''),
  'city' => env('CITY', ''),
  'state' => env('STATE', ''),
  'zip_code' => env('ZIP_CODE', ''),
  'phone' => env('PHONE', ''),
  'email' => env('EMAIL', ''),
  'logo_url' => env('LOGO_URL', ''),
  'bank_account' => [
    'agency' => env('AGENCY', ''),
    'account_number' => env('ACCOUNT_NUMBER', ''),
    'wallet' => env('WALLET', ''),
    'client_code' => env('CLIENT_CODE', ''),
    'accont_digit' => env('ACCOUNT_DIGIT', ''),
    'agency_digit' => env('AGENCY_DIGIT', ''),
    'document_number' => env('DOCUMENT_NUMBER', ''),
  ],
];

<?php

namespace Diadal\Kuda;

use Diadal\Kuda\KudaEncyption;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class KudaOpenApi
{


  /**
   * data
   *
   * @var mixed
   */
  protected $data;

  /**
   * client_key
   *
   * @var string
   */
  protected $client_key;

  /**
   * public_key
   *
   * @var string
   */
  protected $public_key;

  /**
   * private_key
   *
   * @var string
   */
  protected $private_key;

  /**
   * base_url
   *
   * @var string
   */
  protected $base_url;

  /**
   * __construct
   *
   * @return void
   */
  public function __construct()
  {
    $this->client_key =  strval(config('kuda.client_key'));
    $this->public_key =  strval(config('kuda.public_key'));
    $this->private_key =  strval(config('kuda.private_key'));
    $this->base_url =  strval(config('kuda.base_url'));
  }



  /**
   * GetBankList
   *
   * @param  string $requestRef
   * @return array
   */
  public  function GetBankList($requestRef = '')
  {
    $payload = ['ServiceType' => 'BANK_LIST', 'RequestRef' => $requestRef];
    $bank_list = (array) $this->FetchKuda($payload);
    return $bank_list;
  }


  /**
   * OtherMethods
   *
   * @param  array $data
   * @param  string $serviceType
   * @param  string|int $requestRef
   * @param  string $requestType
   * @return array
   */
  public  function OtherMethods(array $data, string $serviceType, $requestRef, string $requestType = 'POST')
  {
    $payload  = [
      'ServiceType' => $serviceType,
      'RequestRef' => $requestRef,
      'Data' => $data
    ];
    $result =  $this->FetchKuda($payload, $requestType);
    return $result;
  }

  /**
   * NameEnquiry
   *
   * @param  array $data
   * @param  string|int $requestRef
   * @return array
   */
  public  function NameEnquiry(array $data, $requestRef = '')
  {
    $NAME_ENQUIRY  = ['ServiceType' => 'NAME_ENQUIRY', 'RequestRef' => $requestRef, 'Data' => $data];
    $result =  $this->FetchKuda($NAME_ENQUIRY);
    return $result;
  }



  /**
   * TransactionsBalance
   *
   * @param  array $data
   * @param  string|int $requestRef
   * @return array
   */
  public  function TransactionsBalance(array $data, $requestRef)
  {
    $payload = ['ServiceType' => "TRANSACTIONS_AND_BALANCE_ENQUIRY", 'RequestRef' => $requestRef, 'Data' => $data];
    $transactions =  $this->FetchKuda($payload);

    return $transactions;
  }

  /**
   * CreateVirtualAccount
   *
   * @param  array $data
   * @param  string|int $requestRef
   * @return array
   */
  public  function CreateVirtualAccount(array $data, $requestRef)
  {
    $payload = ['ServiceType' => "CREATE_VIRTUAL_ACCOUNT", 'RequestRef' => $requestRef, 'Data' => $data];
    $result =  $this->FetchKuda($payload);

    return $result;
  }

  /**
   * OnBoarding
   *
   * @param  array $data
   * @param  string|int $requestRef
   * @return array
   */
  public  function OnBoarding(array $data, $requestRef)
  {
    $payload = ['ServiceType' => "ONBOARDING", 'RequestRef' => $requestRef, 'Data' => $data];
    $result =  $this->FetchKuda($payload);

    return $result;
  }

  /**
   * SingleFundTransfer
   *
   * @param  array $data
   * @param  string|int $requestRef
   * @return array
   */
  public  function SingleFundTransfer(array $data, $requestRef)
  {
    $payload = ['ServiceType' => "SINGLE_FUND_TRANSFER", 'RequestRef' => $requestRef, 'Data' => $data];
    $result =  $this->FetchKuda($payload);

    return $result;
  }

  /**
   * RetrieveVirtualAccount
   *
   * @param  array $data
   * @param  string|int $requestRef
   * @return array
   */
  public  function RetrieveVirtualAccount(array $data, $requestRef)
  {
    $payload = ['ServiceType' => "RETRIEVE_VIRTUAL_ACCOUNT", 'RequestRef' => $requestRef, 'Data' => $data];
    $result =  $this->FetchKuda($payload);
    return ($result);
  }

  /**
   * VirtualAccountFundTransfer
   *
   * @param  array $data
   * @param  string|int $requestRef
   * @return array
   */
  public  function VirtualAccountFundTransfer(array $data, $requestRef)
  {
    $payload = ['ServiceType' => "VIRTUAL_ACCOUNT_FUND_TRANSFER", 'RequestRef' => $requestRef, 'Data' => $data];
    $result =  $this->FetchKuda($payload);

    return $result;
  }



  /**
   * FetchKuda
   *
   * @param  array $data
   * @param  string $requestType
   * @return array
   */
  public  function FetchKuda($data, $requestType = 'POST')
  {

    $data_need_encrypt = json_encode($data);

    $encryption = new KudaEncyption();
    $random_str = Str::random();
    $aes_password = $this->client_key . '-' . $random_str;
    $ecrypted_data =  $encryption->AESEncrypt(strval($data_need_encrypt), $this->client_key, $random_str);
    $ecrypted_password =  $encryption->RSAEncrypt($aes_password, $this->public_key);
    $request_body =   ['data' => $ecrypted_data];
    $payload = (string) json_encode($request_body);
    $headers = [
      'Accept: */*',
      'Content-Encoding: gzip',
      'Content-Type: application/json',
      "password: $ecrypted_password"
    ];
    $result0 = Http::withHeaders($headers)->$requestType($this->base_url, $payload);

    $result = $result0->json();
    $response = $result;
    if (isset($response['data'])) {
      try {
        $decrypted_password =  $encryption->RSADecrypt(strval($response['password']), $this->private_key);

        $explode = explode('-', $decrypted_password);
        $password = $explode[0];
        $salt = $explode[0] ?? '';
        $decrypted_data =  $encryption->AESDecrypt(strval($response['data']), $password, $salt);
        return (array) json_decode($decrypted_data, true);
      } catch (\Throwable $th) {
        throw $th;
      }
    }
    return ['status' => false, $result];
  }
}
<?php

namespace App\Http\Controllers;

class WinpayController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   private $token="";
   private $merchant_key="";
  
    public function __construct()
    {
      $this->token="935381d8c51bc3a493da278a6f611b41";
      $this->merchant_key = "c9c64d57f0c606ef06c297f96697cab4";
		
    }

    //

    public function payload($output){
    	

$messageEncrypted = OpenSSLEncrypt($output, $this->token);
$orderdata = substr($messageEncrypted, 0, 10). $this->token. substr($messageEncrypted, 10);
return $orderdata;
    }

 public function OpenSSLEncrypt($message, $key){
	$output = false;
	$encrypt_method = "AES-256-CBC";
	$secret_key = $key;
	$secret_iv = $key;
	$key = hash('sha256', $secret_key);
	$iv = substr(hash('sha256', $secret_iv), 0, 16);
	$output = openssl_encrypt($message, $encrypt_method, $key, 0, $iv);
	$output = trim(base64_encode($output));
	return $output;
}
public function signature($spi_token, $spi_amount, $spi_merchant_transaction_reff){

// $order_id = "5e4ce7cf79011";
$spi_amount = number_format(doubleval($spi_amount),2,".","");
$spi_signature = strtoupper(sha1( $spi_token . '|' . $this->merchant_key . '|' . $spi_merchant_transaction_reff . '|' . $spi_amount . '|0|0' ));
return $spi_signature;

//spi_signature : DB05BC0D643129A3654114DA45554E32DFB8A7EB
}

}

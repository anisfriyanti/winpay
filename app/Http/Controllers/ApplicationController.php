<?php

namespace App\Http\Controllers;
use App\Http\Controllers\WinpayController;

class ApplicationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }
public function checkout(){

  $data = array();
array_push($data,array(
      "name"=>"Susu Pediasure Complete",
      "sku"=> "20200301001",
      "qty"=>2,
      "unitPrice"=> 150000,
      "desc"=> ""
));
array_push($data,array(
      "name"=> "Deodorant AXL",
      "sku"=> "20200301002",
      "qty"=> 1,
      "unitPrice"=> 58000,
      "desc"=> ""
));
  $this->buyproduct($data);
}
    public function buyproduct($checkout){

        $channel_code="BRIVA";// jika channel code menggunakan BRI VA
        $channel_code="DANAMON";// jika channel code menggunakan DANAMON ONLINE
}
$output['cms']="WINPAY API";
$output['spi_callback']="https://callback.ayambakar.com";
$output['url_listener']="https://sandbox-payment.ayambakar.com"; // URL MERCHANT misal merchant ayambakar
$output['spi_currency']="IDR";
$output['spi_item']=$checkout;

$output['spi_amount']='208000';
 $winpay=new WinpayController;

$output['spi_signature']=$winpay->signature($output['spi_amount']);  
$output['spi_token']=$this->generatetoken();
$output['spi_merchant_transaction_reff']="5e4ce7cf79011";
$output['spi_billingPhone']="081234567890";
$output['spi_billingEmail']="ateng@winpay.id";
$output['spi_paymentDate']=$this->str_date();
$output['get_link']="no";
$payload=$winpay->payload($output);
$orderdata='orderdata='.$payload;
return $this->finalpayment($orderdata, $channel_code);
    }
public function str_date(){
//jika batas dari pembayaran 5 minute
    return strtotime(date('Y-m-d H:i:s' ), '5 minute')

}

 public function generatetoken(){
$private_key1 = "9220fbdeb1d115a4f2e9b2636edc24cc";
$private_key2 = "5b74d200096570de0280b9838c7af1ab";
$base64_encoded_private_key = base64_encode($private_key1 . ":" . $private_key2);
   $ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://sandbox-payment.winpay.id/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


$headers = array();
$headers[] = 'Content-Type: application/x-www-form-urlencoded';
$headers[] = 'Authorization: Basic '.$base64_encoded_private_key;
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

        $data = json_decode($result, true);
        return $data['data']['token'];
 }  
public function finalpayment($orderdata, $channel_code){
    $ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://sandbox-payment.winpay.id/apiv2/".$channel_code);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

curl_setopt($ch, CURLOPT_POSTFIELDS, $orderdata);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/x-www-form-urlencoded"
));

$response = curl_exec($ch);
curl_close($ch);
 $data = json_decode($response, true);
 return $data;
}
public function redirect_channel($redirect_channel_code, $orderdata){
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://sandbox-payment.winpay.id/apiv2/".$redirect_channel_code);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

curl_setopt($ch, CURLOPT_POSTFIELDS, $orderdata);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/x-www-form-urlencoded"
));

$response = curl_exec($ch);
curl_close($ch);
}
public function url_listner(){

}
public function virtualaccount($orderdata, $va_channel_code){
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://sandbox-payment.winpay.id/apiv2/".$va_channel_code);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);

curl_setopt($ch, CURLOPT_POST, TRUE);

curl_setopt($ch, CURLOPT_POSTFIELDS, $orderdata);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  "Content-Type: application/x-www-form-urlencoded"
));

$response = curl_exec($ch);
curl_close($ch);
}
}

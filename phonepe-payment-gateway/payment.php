<?php 
     $data = [
    "merchantId"=> "PGTESTPAYUAT",
    "merchantTransactionId"=> "MT7850590068188104",
    "merchantUserId"=> "MUID123",
    "amount"=> 10000,
    "redirectUrl"=>"http://localhost/Vyza/phonepe-payment-gateway/redirect-url.php",
    "redirectMode"=> "POST",
    "callbackUrl"=> "http://localhost/Vyza/phonepe-payment-gateway/callback-url.php",
    "mobileNumber"=> "9999999999",
    "paymentInstrument" => [
      "type" => "PAY_PAGE"
     ]
    
];

$saltKey = '099eb0cd-02cf-4e2a-8aca-3e6c6aff0399';
$saltIndex = 1;
$encode = json_encode($data);
$encoded = base64_encode($encode);
$string = $encoded.'/pg/v1/pay'.$saltKey;
$sha256 = hash('sha256',$string);
$finalXHeader = $sha256.'###'.$saltIndex;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay");
curl_setopt($ch, 
         CURLOPT_HTTPHEADER,
         array(
            'Content-Type:application/json',
            'accept:application/json',
            'X-VERIFY: '.$finalXHeader,
         )
         );
         curl_setopt($ch,CURLOPT_POST,1);
         curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode(array('request' => $encoded)));
         curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

         $response = curl_exec($ch);

         $final = json_decode($response,true);


        header('location:'.$final['data']['instrumentResponse']['redirectInfo']['url']);
        exit();

  
?>
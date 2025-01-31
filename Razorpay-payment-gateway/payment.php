
<?php
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST,GET,PUT,PATCH,DELETE');
header("Content-Type: application/json");
header("Accept: application/json");
header('Access-Control-Allow-Headers:Access-Control-Allow-Origin,Access-Control-Allow-Methods,Content-Type');

if(isset($_POST['action']) && $_POST['action']='payOrder'){

$razorpay_mode='live';

// $razorpay_test_key='rzp_test_2IB6uvZrsVpjrT'; //Your Test Key
// $razorpay_test_secret_key='9s5ikwBBP8yg4QT1NXUOQT19'; //Your Test Secret Key

$razorpay_live_key= 'rzp_live_5BrS6Gldav87Fs';
$razorpay_live_secret_key='NU8QmEi9MzHCMlSK0dyhWC6q';

if($razorpay_mode=='test'){
    
    $razorpay_key=$razorpay_test_key;
    
$authAPIkey="Basic ".base64_encode($razorpay_test_key.":".$razorpay_test_secret_key);

}else{
    
	$authAPIkey="Basic ".base64_encode($razorpay_live_key.":".$razorpay_live_secret_key);
	$razorpay_key=$razorpay_live_key;

}

// Set transaction details
$order_id = uniqid(); 

$billing_serial=$_POST['billing_serial'];
$billing_name=$_POST['billing_name'];
$billing_price=$_POST['billing_price'];
$billing_quantity=$_POST['billing_quantity'];
$shipping_serial=$_POST['shipping_serial'];
$shipping_name=$_POST['shipping_name'];
$shipping_price=$_POST['shipping_price'];
$shipping_quantity=$_POST['shipping_quantity'];
$paymentOption=$_POST['paymentOption'];
$payAmount=$_POST['payAmount'];

$note="Payment of amount Rs. ".$payAmount;

$postdata=array(
"amount"=>$payAmount*100,
"currency"=> "INR",
"receipt"=> $note,
"notes" =>array(
	          "notes_key_1"=> $note,
	          "notes_key_2"=> ""
              )
);
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.razorpay.com/v1/orders',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>json_encode($postdata),
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: '.$authAPIkey
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$orderRes= json_decode($response);
 
if(isset($orderRes->id)){
 
$rpay_order_id=$orderRes->id;
 
$dataArr=array(
	'amount'=>$payAmount,
	'description'=>"Pay bill of Rs. ".$payAmount,
	'rpay_order_id'=>$rpay_order_id,
	'serial'=>$billing_serial,
	'name'=>$billing_name,
	'price'=>$billing_price
);
echo json_encode(['res'=>'success','order_number'=>$order_id,'userData'=>$dataArr,'razorpay_key'=>$razorpay_key]); exit;
}else{
	echo json_encode(['res'=>'error','order_id'=>$order_id,'info'=>'Error with payment']); exit;
}
}else{
    echo json_encode(['res'=>'error']); exit;
}
       ?>   

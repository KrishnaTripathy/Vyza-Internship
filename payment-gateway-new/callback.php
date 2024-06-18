<?php


require_once './vendor/autoload.php';
require_once 'phonepe_integration.php';


$isValid = $phonePePaymentsClient->verifyCallback($response, $xVerify);

if ($isValid) {
    echo "valid";
} else {
    echo "not valid";
}

?>

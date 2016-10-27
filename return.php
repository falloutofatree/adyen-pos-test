<?php
  // shamelessly copy and past (and adapted) from: https://github.com/Adyen/adyen-pos-quick-integration-sample-code/blob/master/Web%20to%20App/1.%20PHP%20-%20pos-payment/pos-landing.php
  #session_id($_GET['sessid']);
  session_start();

  if ($_SESSION['merchantAccount'] != $_GET['merchantAccount'] || $_SESSION['merchantReference'] != $_GET['merchantReference']){
    die("this request was manipulated");
  }
  if (isset($_GET['checksum'])){
    $cs = $_GET['checksum'];
  }elseif(isset($_GET['cs'])){
    $cs = $_GET['cs'];
  }else{
    $cs = 0;
  }

  $status = "UNKNOWN";

  $authResult = $_GET['result'];
  if (empty($authResult) || $authResult == ""){
    die("there was a error");
  }

  if ($authResult == "CANCELLED" || $authResult == "DECLINED" || $authResult == "ERROR" || $authResult == "(null)"){
    $status = $authResult;
    print "Cancelled";
  }
  elseif($authResult == "APPROVED"){
    $amount = $_GET['amountValue'];
    $currency = $_GET['amountCurrency'];
    $sessionid = $_GET['sessionId'];

    if (validatechecksum($amount,$currency,$authResult,$sessionid,$cs,DEBUG) ==  false){
      die('checksum failing');
    }
    $status = "APPROVED";
    $_SESSION['transaction_result'] = $status;
  }


  function validatechecksum($amount,$currency,$result,$sessionid,$cs,$debugcs=false){
    $amountdigits = str_split($amount);
    $currencychars = str_split($currency);
    $resultchars = str_split($result);
    $sessiondigits = str_split($sessionid);

    $checksum = 0;

    foreach ($amountdigits as $value){
      $checksum += Ascii2Int($value);
    }

    foreach ($currencychars as $value){
      $checksum += Ascii2Int($value);
    }

    foreach ($resultchars as $value){
      $checksum += Ascii2Int($value);
    }

    $multiplier = 0;
    foreach ($sessiondigits as $value){
      $multiplier += Ascii2Int($value);
    }

    if ($multiplier == 0){
      $checksum = $checksum % 100;
    }else{
      $checksum = ($checksum * $multiplier) % 100;
    }

    if ( $cs != $checksum ) {
      return false;
    }

    return true;
  }
  function Ascii2Int($ascii){
    if (is_numeric($ascii)){
      $int = ord($ascii) - 48;
    } else {
      $int = ord($ascii) - 64;
    }
    return $int;
  }

 ?>
<script language="javaScript">
  function closeWindow() {
    window.open('', '_self', '');
    window.close();
  }
  setTimeout(closeWindow, 500);
</script>
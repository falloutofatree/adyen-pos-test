<?php
  session_start();

  $merchantAccount   = "FlightClubUSPOS";
  $merchantReference = "TEST-PAYMENT-" . date("Y-m-d-H:i:s");

  $_SESSION['_sid'] = session_id();
  $_SESSION["merchantAccount"]   = $merchantAccount;
  $_SESSION["merchantReference"] = $merchantReference;
?>
<!DOCTYPE html>
<html>
<head>
  <title>Adyen</title>
</head>
<body>


  <h1>Click on the button to make a purchase</h1>
  <a href="#">Buy me!</a>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script type="text/javascript">
    (function($) {
      var requestObject = {}

      // var envUrl = "http://localhost/";
      var envUrl = "http://sellry.com/adyen-pos/";

      var baseUrl = "adyen://payment/?";

      requestObject.sessionId = "1373375213ABCD";
      requestObject.amount = "2000"; // = 20,00 EUR
      requestObject.currency = "EUR";
      requestObject.merchantReference = "<?php echo $merchantReference ?>";
      requestObject.callback = envUrl + 'return.php?<?php echo urlencode("sessid=" . $sessid . "&merchantAccount=" . $merchantAccount . "&merchantReference=" . $merchantReference); ?>';

      function buildUrl(baseUrl, obj) {
        var parameters = $.param(obj)
        return baseUrl + parameters;
      }

      $(function() {
        $('a').click(function () {
            window.location.assign(buildUrl(baseUrl, requestObject));
        })
      });
    })(jQuery);
  </script>
</body>
</html>
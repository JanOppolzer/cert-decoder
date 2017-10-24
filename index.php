<!DOCTYPE html>

<html>
<head>
    <title>Certificate Decoder</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/normalize.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

<h1>Certificate Decoder</h1>
<?php

if(strcmp($_POST["decode"], "yes") === 0) {
    $CERTIFICATE = $_POST["certificate"];
    $START       = "-----BEGIN CERTIFICATE-----";
    $END         = "-----END CERTIFICATE-----";

    if(preg_match("/$START/", $CERTIFICATE) === 1) {
        $START = "";
    }

    if(preg_match("/$END/", $CERTIFICATE) === 1) {
        $END = "";
    }

    $X509Certificate =  $START . "\n" . trim ($CERTIFICATE) . "\n" . $END;
    $cert = openssl_x509_parse($X509Certificate, true);
?>

<table>
    <tr>
        <td>Common Name</td>
        <td><?=$cert["subject"]["CN"]?></td>
    </tr>
    <tr>
        <td>Subject Alternative Names</td>
        <td><?=$cert["extensions"]["subjectAltName"]?></td>
    </tr>
    <tr>
        <td>Organization</td>
        <td><?=$cert["subject"]["O"]?></td>
    </tr>
    <tr>
        <td>Locality</td>
        <td><?=$cert["subject"]["L"]?></td>
    </tr>
    <tr>
        <td>State</td>
        <td><?=$cert["subject"]["ST"]?></td>
    </tr>
    <tr>
        <td>Country</td>
        <td><?=$cert["subject"]["C"]?></td>
    </tr>
    <tr>
        <td>Valid From</td>
        <td><?=date("Y-m-d", $cert["validFrom_time_t"])?></td>
    </tr>
    <tr>
        <td>Valid To</td>
        <td><?=date("Y-m-d", $cert["validTo_time_t"])?></td>
    </tr>
    <tr>
        <td>Issuer</td>
        <td><?=$cert["issuer"]["CN"]?>, <?=$cert["issuer"]["O"]?>, <?=$cert["issuer"]["L"]?>, <?=$cert["issuer"]["ST"]?>, <?=$cert["issuer"]["C"]?></td>
    </tr>
    <tr>
        <td>Serial Number</td>
        <td><?=$cert["serialNumberHex"]?></td>
    </tr>
    <tr>
        <td>Key Usage</td>
        <td><?=$cert["extensions"]["keyUsage"]?></td>
    </tr>
    <tr>
        <td>Extended Key Usage</td>
        <td><?=$cert["extensions"]["extendedKeyUsage"]?></td>
    </tr>
    <tr>
        <td>CRL Distribution Points</td>
        <td>
<pre><?=$cert["extensions"]["crlDistributionPoints"]?></pre>
        </td>
    </tr>
</table>

<p><a href=".">Decode another certificate</a></p>
<?php

} else {

?>

<p><em>Tip:</em> Insert a certificate below either with starting
<code>-----BEGIN CERTIFICATE-----</code> and ending <code>-----END
CERTIFICATE-----</code> or without it.</p>

<form action="./index.php" method="post">
    <input type="hidden" name="decode" value="yes">
    <textarea cols="70" rows="35" name="certificate" placeholder="Insert a certificate to decode" required autofocus></textarea>
    <br>
    <input type="submit" value="Decode">
</form>
<?php

}

?>

</body>
</html>


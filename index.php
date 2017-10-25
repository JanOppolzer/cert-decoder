<?php

/* find user's preferred locales
 */
if(preg_match("/cs|sk/", $_SERVER["HTTP_ACCEPT_LANGUAGE"])) {
    $locale = "czech";
}

/* do we have a certificate submitted?
 */
function decode() {
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
        return $cert = openssl_x509_parse($X509Certificate, true);
    }
}

?>
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

<?php

switch($locale) {
    case "czech":
        echo "<h1>Dekodér certifikátů</h1>\n";
        break;
    default:
        echo "<h1>Certificate Decoder</h1>\n";
        break;
}

$cert = decode();
if(!is_null($cert)) {

    if(!is_array($cert)) {

        switch($locale) {
            case "czech":
                echo "<p>Tohle není certifikát.</p>\n";
                echo "<p><a href=\".\">Dekódovat další certifikát</a></p>\n";
                break;
            default:
                echo "<p>Not a certificate.</p>\n";
                echo "<p><a href=\".\">Decode another certificate</a></p>\n";
                break;
        }

    } else {

    switch($locale) {
        case "czech":
?>

<table>
    <tr>
        <td>Common Name</td>
        <td><?=$cert["subject"]["CN"]?></td>
    </tr>
    <tr>
        <td>Alternativní jména</td>
        <td><?=$cert["extensions"]["subjectAltName"]?></td>
    </tr>
    <tr>
        <td>Organizace</td>
        <td><?=$cert["subject"]["O"]?></td>
    </tr>
    <tr>
        <td>Lokalita</td>
        <td><?=$cert["subject"]["L"]?></td>
    </tr>
    <tr>
        <td>Stát (město)</td>
        <td><?=$cert["subject"]["ST"]?></td>
    </tr>
    <tr>
        <td>Země</td>
        <td><?=$cert["subject"]["C"]?></td>
    </tr>
    <tr>
        <td>Platný od</td>
        <td><?=date("d. m. Y", $cert["validFrom_time_t"])?></td>
    </tr>
    <tr>
        <td>Platný do</td>
        <td><?=date("d. m. Y", $cert["validTo_time_t"])?></td>
    </tr>
    <tr>
        <td>Vydavatel</td>
        <td><?=$cert["issuer"]["CN"]?>, <?=$cert["issuer"]["O"]?>, <?=$cert["issuer"]["L"]?>, <?=$cert["issuer"]["ST"]?>, <?=$cert["issuer"]["C"]?></td>
    </tr>
    <tr>
        <td>Sériové číslo</td>
        <td><?=$cert["serialNumberHex"]?></td>
    </tr>
    <tr>
        <td>Použití</td>
        <td><?=$cert["extensions"]["keyUsage"]?></td>
    </tr>
    <tr>
        <td>Rozšířené použití</td>
        <td><?=$cert["extensions"]["extendedKeyUsage"]?></td>
    </tr>
    <tr>
        <td>Distribuční body CRL</td>
        <td>
<pre><?=$cert["extensions"]["crlDistributionPoints"]?></pre>
        </td>
    </tr>
</table>

<p><a href=".">Dekódovat další certifikát</a></p>
<?php
            break;
        default:
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
            break;
    }
    }

} else {

    switch($locale) {
        case "czech":
?>

<p><em>Nápověda:</em> Vložte certifikát buď se začínajícím <code>-----BEGIN
CERTIFICATE-----</code> a končícím <code>-----END CERTIFICATE-----</code> nebo
bez nich.</p>

<form action="./index.php" method="post">
    <input type="hidden" name="decode" value="yes">
    <textarea cols="70" rows="35" name="certificate" placeholder="Vložte certifikát k dekódování" required autofocus></textarea>
    <br>
    <input type="submit" value="Decode">
</form>
<?php
            break;
        default:
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
            break;
    }
}

?>

</body>
</html>


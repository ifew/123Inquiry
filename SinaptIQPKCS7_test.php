<?php
//1.Note for Request Message to 123 Service
//You have to request the one line encrypted string to our service becuase PHP produce the encrypted string with carriage return
//********
//2.Note for Reponse Message from 123 Service
//123 Service will response the one line encrypted string to your service. You need to chop our message by 65 length to be suitable with PHP decryption.

$data = "Hello PKCS7";

$key = file_get_contents("123Public.pem"); //public key for encrypt. This is 123's public key
$merchantkey = file_get_contents("MerchantPublic.pem"); //public key for encrypt. This is 123's public key
$key_private = array(file_get_contents("MerchantPrivate(123).pem"), "123"); //private key for decrypt. This is Merchant's private key

$datFile = "C:\\SinaptIQPKCS7\\msg.txt";
$encFile = "C:\\SinaptIQPKCS7\\enc.txt";
$decFile = "C:\\SinaptIQPKCS7\\dec.txt";

//Open text file and write $data into text file
$fp = fopen($datFile, "w");
fwrite($fp, $data);
fclose($fp);

encrypt($key, $datFile, $encFile);
decrypt($encFile, $decFile, $merchantkey, $key_private);

function encrypt($key, $datFile, $encFile){
    if (openssl_pkcs7_encrypt($datFile, $encFile, $key, array())) {

        echo "<b>Successfully encrypted: </b>";

        $tempStr = file_get_contents($encFile);
        $strOri = "MIME-Version: 1.0
Content-Disposition: attachment; filename=\"smime.p7m\"
Content-Type: application/x-pkcs7-mime; smime-type=enveloped-data; name=\"smime.p7m\"
Content-Transfer-Encoding: base64

";
       $fp = fopen($encFile, "w");
       fwrite($fp, str_replace($strOri,"",$tempStr));
       fclose($fp);
        
        echo str_replace($strOri,"",$encFile)."<br/><br/>";

       echo "<b>Encrypted string again, with \"\\n\" replaced with &lt;br&gt and \"\\r\" replaced with [CR]:</b><br>";

       $fp = fopen($encFile, 'r');

       while (false !== ($char = fgetc($fp))) {

           if ($char == "\n") echo "<br>";
           else if ($char == "\r") echo "[CR]";
           echo $char;
       }
    }else 
        echo "Cannot Encrypt <br/>";
}

function decrypt($encFile, $decFile, $key, $key_private){
    $strOri = "MIME-Version: 1.0
Content-Disposition: attachment; filename=\"smime.p7m\"
Content-Type: application/x-pkcs7-mime; smime-type=enveloped-data; name=\"smime.p7m\"
Content-Transfer-Encoding: base64

";
    $enc = file_get_contents($encFile);
    $enc = wordwrap($enc, 64, "\n", true);
    $fp = fopen($encFile, "w");
    fwrite($fp, $strOri.$enc);
    fclose($fp);

    if (openssl_pkcs7_decrypt($encFile, $decFile, $key, $key_private)) {
        
        echo "<br><b>Successfully decrypted: </b>";
        echo file_get_contents($decFile);
    }else 
        echo "Cannot Decrypt";
}

?>
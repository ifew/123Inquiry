<?php
//1.Note for Request Message to 123 Service
//You have to request the one line encrypted string to our service becuase PHP produce the encrypted string with carriage return
//********
//2.Note for Reponse Message from 123 Service
//123 Service will response the one line encrypted string to your service. You need to chop our message by 65 length to be suitable with PHP decryption.


//Open text file and write $data into text file

function encrypt($key, $msg){
	
	$msgfile="msg.txt";
	$encfile="enc.txt";
	$decfile="dec.txt";
	file_put_contents($msgfile,$msg); 
    if (openssl_pkcs7_encrypt($msgfile, $encfile, $key, array())) {

        echo "<b>Successfully encrypted: </b>";

        $tempStr = file_get_contents($encfile);
		$pos = strpos($tempStr, "base64");
		$tempStr=trim(substr($tempStr,$pos+6));

         
        return str_replace($strOri,"",$tempStr);
      
    }else 
	{
		echo "Cannot Encrypt <br/>";
        return "Cannot Encrypt";
	}
}

function decrypt($encText,$key, $key_private){
		
	$msgfile="msg.txt";
	$encfile="enc.txt";
	$decfile="dec.txt";
	file_put_contents($encfile,$encText); // π”‰ø≈Ï≈ß‰ª„π msg.txt Merchant's private key

    $strOri = "MIME-Version: 1.0
Content-Disposition: attachment; filename=\"smime.p7m\"
Content-Type: application/x-pkcs7-mime; smime-type=enveloped-data; name=\"smime.p7m\"
Content-Transfer-Encoding: base64

";
    $enc = file_get_contents($encfile);
    $enc = wordwrap($enc, 64, "\n", true);
    $fp = fopen($encfile, "w");
    fwrite($fp, $strOri.$enc);
    fclose($fp);

    if (openssl_pkcs7_decrypt($encfile, $decfile, $key, $key_private)) {
        
       // echo "<br><b>Successfully decrypted: </b>";
        return file_get_contents($decfile);
    }else 
	{
        echo "Cannot Decrypt";
		return "Cannot Decrypt";
	}
}

?>
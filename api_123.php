<?php
include("SinaptIQPKCS7.php"); // เรียก function ที่ทาง 2c2p ส่งมาให้

//Pleae change THIS For Production
$onetwothreefilecer="123Public.pem"; //123' Certificate file
$merchantcer="MerchantPublic.pem";//Merchant' Certificate file
$merchantprivate="MerchantPrivate(123).pem";//Merchant' Private Key file
$merchantpassword="123";//Merchant' Password for Private Key file
//END Pleae change THIS For Production

//Please change MERCHANT INFO
$MerchantID='merchant@smarthotel.com';//Merchant ID
$apikey="M5WCTP59J544IRRUBTJE0Q7Z2PAJX3CT";//API Secret Key
//END Please change MERCHANT INFO

//Data///
$RefNo1='122233';
$Version="1.1";
$TimeStamp="2013-05-21 11:11:12:888";
$MessageID="123456abcdefhig";
$InvoiceNo='1234123';
$Amount=100;
$Amount=str_pad("$Amount",12,"0",STR_PAD_LEFT);
$Discount=str_pad("$Discount",12,"0",STR_PAD_LEFT);
$ServiceFee=str_pad("$Discount",12,"0",STR_PAD_LEFT);
$ShippingFee=str_pad("$Discount",12,"0",STR_PAD_LEFT);
$CurrencyCode="THB";
$CountryCode="THA";
$ProductDesc="Designer Hats";
$PaymentItems="<PaymentItem id=\"1\" name=\"Hat Blue XL\" price=\"000000300000\" quantity=\"1\" />";
$PayerName="$Developer";
$PayerEmail="developer@gmail.com";
$ShippingAddress="Bangkok";
$MerchantUrl="https://www.merchant.com/frontend.aspx";
$APICallUrl="https://www.merchant.com/backend.aspx";
$AgentCode="BBL";
$ChannelCode="ATM";
$PayInSlipInfo="";
$UserDefined1="";
$UserDefined2="";
$UserDefined3="";
$UserDefined4="";
$UserDefined5="";

//END Data///

$signData = hash_hmac('sha1', "$MerchantID$InvoiceNo$Amount",$apikey, false);
$signData =  strtoupper($signData);
$HashValue= urlencode($signData);
$msg= "
<OneTwoThreeReq>
	<Version>$Version</Version>
	<TimeStamp>$TimeStamp</TimeStamp>
	<MessageID>$MessageID</MessageID>
	<MerchantID>$MerchantID</MerchantID>
	<InvoiceNo>$InvoiceNo</InvoiceNo>
	<Amount>$Amount</Amount>
	<Discount>$Discount</Discount>
	<ServiceFee>$ServiceFee</ServiceFee>
	<ShippingFee>$ShippingFee</ShippingFee>
	<CurrencyCode>$CurrencyCode</CurrencyCode>
	<CountryCode>$CountryCode</CountryCode>
	<ProductDesc>$ProductDesc</ProductDesc>
	<PaymentItems>
		$PaymentItems
	</PaymentItems>
	<PayerName>$PayerName</PayerName>
	<PayerEmail>$PayerEmail</PayerEmail>
	<ShippingAddress>$ShippingAddress</ShippingAddress>
	<MerchantUrl>$MerchantUrl</MerchantUrl>
	<APICallUrl>APICallUrl</APICallUrl>
	<AgentCode>$AgentCode</AgentCode>
	<ChannelCode>$ChannelCode</ChannelCode>
	<PayInSlipInfo>$PayInSlipInfo</PayInSlipInfo>
	<UserDefined1></UserDefined1>
	<UserDefined2></UserDefined2>
	<UserDefined3></UserDefined3>
	<UserDefined4></UserDefined4>
	<UserDefined5></UserDefined5>
	<HashValue>$HashValue</HashValue>
</OneTwoThreeReq>
";


$key = file_get_contents($onetwothreefilecer); //public key for encrypt. This is 123's public key
$merchantkey = file_get_contents($merchantcer); //public key for encrypt. This is 123's public key
$key_private = array(file_get_contents($merchantprivate), $merchantpassword); //private key for decrypt. This is 

$OneTwoThreeReq=encrypt($key, $msg);

print "
<Form method=post action='http://uat.123.co.th/payment/paywith123.aspx'>
<input type='hidden' name='OneTwoThreeReq' value='$OneTwoThreeReq'>
<input type='submit' value='Send' name='submit'> </Form>
";

$OneTwoThreeRes=decrypt($OneTwoThreeReq, $key, $key_private);
echo "<br>Decryption : ".$OneTwoThreeRes;
?>

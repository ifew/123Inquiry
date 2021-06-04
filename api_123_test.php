<?php
$RefNo1='123';
$Version="1.1";
$TimeStamp="2012-03-21 11:11:12:888";
$MessageID="123456abcdefhig";
$MerchantID='merchant@merchant.co.th';
$InvoiceNo='123';
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
$HashValue="";

#$signString="$MerchantID$InvoiceNo$RefNo1";
#$signData = hash_hmac('sha1', "$signString",'HFM5BA08LPZ374FX5V9D60FQ0X1QDCS6', false);
$signData = hash_hmac('sha1', "$MerchantID$InvoiceNo$Amount",'HFM5BA08LPZ374FX5V9D60FQ0X1QDCS6', false);
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
$path[one23]="/Users/home/public_html/PKCS7&Hash";
file_put_contents("$path[one23]/msg/msg.txt",$msg); // นำไฟล์ลงไปใน msg.txt
include("$path[one23]/PHP/SinaptIQPKCS7.php"); // เรียก function ที่ทาง 2c2p ส่งมาให้
$OneTwoThreeReq=file_get_contents("$path[one23]/msg/enc.txt"); // ดึงข้อมูลไฟล์  enc แล้วนำมาใส่ที่ form 
print "
<Form method=post action='http://uat.123.co.th/payment/paywith123.aspx'>
<input type='hidden' name='OneTwoThreeReq' value='$OneTwoThreeReq'>
<input type='submit' value='Send' name='submit'> </Form>
";
?>

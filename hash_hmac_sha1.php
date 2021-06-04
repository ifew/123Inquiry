<?php

$signData = hash_hmac('sha1', "hello",'746D7SCHAIQ0QUZ0MRJWU0PQ3AD7PJ8B', false);
$signData =  strtoupper($signData);
echo urlencode($signData);
 ?>
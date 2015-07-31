<?php

$deviceToken = 'f634161c87813600a51e76e2b087ad48868513a78976cc0af339a1ffd2cef077';
$passphrase = 'RamsHeadGroup';
$pemfile = 'RHg.pem';
$message = 'This is a push message for the RamsHeadGroup users;)';
$ctx = stream_context_create();
stream_context_set_option($ctx, 'ssl', 'local_cert', $pemfile);
stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
  
// Open a connection to the APNS server  
$fp = stream_socket_client(  
    'ssl://gateway.sandbox.push.apple.com:2195', $err,  
    $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);  
  
if (!$fp)  
    exit("Failed to connect: $err $errstr" . PHP_EOL);  
  
echo 'Connected to APNS' . PHP_EOL;  
  
// Create the payload body  
$body['aps'] = array(  
    'alert' => $message,  
    'sound' => 'default'  
    );  
  
// Encode the payload as JSON  
$payload = json_encode($body);  
  
// Build the binary notification  
$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;  
  
// Send it to the server  
$result = fwrite($fp, $msg, strlen($msg));  
  
if (!$result)  
    echo 'Message not delivered' . PHP_EOL;  
else  
    echo 'Message successfully delivered' . PHP_EOL;  
  
// Close the connection to the server  
fclose($fp); 
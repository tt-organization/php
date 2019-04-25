<?php
// Create token header as a JSON string
$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charactersLength = strlen($characters);
$randomString = '';
for ($i = 0; $i < 10; $i++) {
	$randomString .= $characters[rand(0, $charactersLength - 1)];
}

// Create token payload as a JSON string
$payload = json_encode(['success' => $randomString]);


// Encode Header to Base64Url String
$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

// Encode Payload to Base64Url String
$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

// Create Signature Hash
$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'abC123!', true);

// Encode Signature to Base64Url String
$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

// Create JWT
$jwt = json_encode( $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature );

echo $jwt;
?>
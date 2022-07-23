<?php
$sessionId = getSessionId($_GET["bookingid"], $_GET["lastname"], $_GET["firstname"]);
$alphas = array_merge(array(""),range('A', 'Z'));
$slots = array();

for($i=0;true;$i++) {
    $data = getTimeSlots($_GET["product"].$alphas[$i], $_GET["date"], $sessionId); 
    if(array_key_exists("productCode", $data)) {
        if(isset($data["availTimes"][0]["freeContingent"])) $slots[] = array($data["productCode"], $data["availTimes"][0]["begin"], $data["availTimes"][0]["freeContingent"]);    
    } else break;
}

if(empty($slots)) exit;

$text = "Dein gesuchter Ausflug ".$_GET["product"]." am ".date("l d.m.Y", strtotime($_GET["date"]))." ist verfügbar:\n\n";
foreach($slots as $slot) {
    $text = $text.$slot[0].": ".date("G:i", strtotime($slot[1]))." (Verfügbarkeit: ".$slot[2].")\n";
}

echo $text;
//mail("test@example.com", "Dein gesuchter AIDA-Ausflug ist verfügbar", $text, "From: noreply <noreply@example.com>");

function getTimeSlots($product, $date, $sessionId) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://hertha.cruise-api.aida.de/products/'.$product.'/timeslots?sessionID='.$sessionId.'&date='.$date);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

    $headers = array();
    $headers[] = 'Authority: hertha.cruise-api.aida.de';
    $headers[] = 'Accept: application/json';
    $headers[] = 'Accept-Language: de-DE,de;q=0.9,en-US;q=0.8,en;q=0.7';
    $headers[] = 'Origin: https://aida.de';
    $headers[] = 'Referer: https://aida.de/';
    $headers[] = 'Sec-Ch-Ua: ^^';
    $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
    $headers[] = 'Sec-Ch-Ua-Platform: ^^Windows^^\"\"';
    $headers[] = 'Sec-Fetch-Dest: empty';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Sec-Fetch-Site: same-site';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.115 Safari/537.36';
    $headers[] = 'X-Api-Key: ===enter x-api-key here===';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = json_decode(curl_exec($ch), true);
    /**
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    */
    curl_close($ch); 
    return $result;
}

function getSessionId($bookingId, $lastName, $firstName) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://hertha.cruise-api.aida.de/booking/'.$bookingId.'/sessions?lastName='.$lastName.'&firstName='.$firstName);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

    $headers = array();
    $headers[] = 'Authority: hertha.cruise-api.aida.de';
    $headers[] = 'Accept: application/json';
    $headers[] = 'Accept-Language: de-DE,de;q=0.9,en-US;q=0.8,en;q=0.7';
    $headers[] = 'Origin: https://aida.de';
    $headers[] = 'Referer: https://aida.de/';
    $headers[] = 'Sec-Ch-Ua: ^^';
    $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
    $headers[] = 'Sec-Ch-Ua-Platform: ^^Windows^^\"\"';
    $headers[] = 'Sec-Fetch-Dest: empty';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Sec-Fetch-Site: same-site';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.115 Safari/537.36';
    $headers[] = 'X-Api-Key: ===enter x-api-key here===';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = json_decode(curl_exec($ch), true);
    /**
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    */
    curl_close($ch); 
    if(isset($result["isError"]) && $result["isError"] == true) return false;
    else return $result["sessionToken"];
}

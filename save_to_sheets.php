<?php
$gasUrl = "https://script.google.com/macros/s/AKfycbxPlDyNCdQp-1Ur6rpuSMhht-prSYtOg4rtGmITAgW3aKXNnLAKpGgMIqjzEams6Pytpg/exec"; // 務必確認是「新部署」後的網址
$coinGeckoUrl = "https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=market_cap_desc&per_page=20&page=1";

// 1. 抓取資料
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $coinGeckoUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, "PHP_Tracker");
$cryptoData = curl_exec($ch);
curl_close($ch);

if ($cryptoData) {
    // 【關鍵修正】重新編碼 JSON，確保沒有奇怪的編碼問題
    $cleanData = json_encode(json_decode($cryptoData, true));

    // 2. 轉傳給 GAS
    $ch = curl_init($gasUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); // 強制使用 POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, $cleanData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($cleanData)
    ));
    
    $result = curl_exec($ch);
    curl_close($ch);
    
    echo "Sync Status: " . htmlspecialchars($result);
}
?>
<?php
/**
 * AJAX Proxy for Live Search & Pagination
 */
$searchName = $_GET['search_name'] ?? '';
$techName = $_GET['tech_name'] ?? '';
$minPrice = $_GET['min_price'] ?? '';
$maxPrice = $_GET['max_price'] ?? '';
$page = $_GET['page'] ?? 1;

$apiUrl = "https://kisanogram.com/ampl-products.php?page=$page&name=" . urlencode($searchName) . "&tech=" . urlencode($techName) . "&min=" . $minPrice . "&max=" . $maxPrice;

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => $apiUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_USERAGENT => "Mozilla/5.0",
    CURLOPT_HTTPHEADER => array("Accept: application/json"),
));

$response = curl_exec($curl);
curl_close($curl);

header('Content-Type: application/json');
echo $response;

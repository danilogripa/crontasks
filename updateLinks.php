<?php 
$linkAPI = "https://api.lomadee.com/v2/15861858315297cc8cf8f/coupon/_all?sourceId=36440063";
date_default_timezone_set('America/Sao_Paulo');
$date = date('dmY');

//===================================//
//$servername = "database-2.crawzmhqfqli.sa-east-1.rds.amazonaws.com";
$servername = "database-2.c0ngzntjbpxs.sa-east-1.rds.amazonaws.com";
$username = "cuponocitybd";
$password = "xb0z8GwoCB";
$db = "exten";
$table = "cupons";

//===================================//


$comAcentos = ['à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'O', 'Ù', 'Ü', 'Ú'];

$semAcentos = ['a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', '0', 'U', 'U', 'U'];


$json = file_get_contents($linkAPI, TRUE);
$json = json_decode($json,TRUE);

//echo "<pre>";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

$truncate = 'TRUNCATE TABLE `'.$db.'`.`'.$table.'`';
$stmt = $conn->prepare($truncate);
$stmt->execute();

// prepare and bind
$stmt = $conn->prepare('INSERT INTO `'.$db.'`.`'.$table.'` (`code`, `vigencyEndDate`, `link`, `discount`, `storeName`, `title`, `storeId`, `categoryName`, `storeImage1`, `categoryId`) VALUES  (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
$stmt->bind_param("sssississi", 
    $code,
    $vigencyEndDate,
    $link,
    $discount,
    $storeName,
    $title,
    $storeid,
    $categoryName,
    $storeImage1,
    $categoryId);

foreach ($json["coupons"] as $key => $value) {
    $code = $value["code"];
    $vigencyEndDate = $value["vigency"];
    $link = $value["link"];
    $discount = $value["discount"];
    $storeName = str_replace($comAcentos, $semAcentos, $value["store"]["name"]);
    $title = str_replace($comAcentos, $semAcentos, $value["description"]);
    $storeid = $value["store"]["id"];
    $categoryName = str_replace($comAcentos, $semAcentos, $value["category"]["name"]);
    $storeImage1 = $value["store"]["image"];
    $categoryId = $value["category"]["id"];
    $stmt->execute();
}

$conn = null;

 ?>

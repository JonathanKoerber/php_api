<?php
// headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charsed=UTF-8");

// db connection
include_once '../config/database.php';
include_once '../objects/product.php';

// db instance
$database = new Database();
$db = $database->getConnection();

// init obj product
$product = new Product($db);

// query products
$stmt = $product->read();
$num = $stmt->rowCount();

if($num>0){
    $products_arr=array();
    $products_arr["records"]=array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $product_item=array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description),
            "price" => $price,
            "category_id" => $category_id,
            "category_name" => $category_name
        );
        array_push($products_arr["records"], $product_item);
    }
    // set response code
    http_response_code(200);

    //show products data in json format
    echo json_encode($products_arr);
}else{
    http_response_code(404);
    echo json_encode(
        array("message"=>"No products found.")
    );
}
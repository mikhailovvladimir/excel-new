<?php
include 'database.php';


$uploadfile = $_FILES['file']['tmp_name'][0];

require 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php';

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    header('Content-Type: application/json; charset=utf-8');
    $response = array();
    $response['status'] = 'bad';
    // открываем существующий файл
    $objExcel = PHPExcel_IOFactory::load($uploadfile);
    foreach ($objExcel->getWorksheetIterator() as $worksheet) {
        $highestrow = $worksheet->getHighestRow();

        for ($row = 0; $row <= $highestrow; $row++) {
            $code = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
            $title = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
            $type_price = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
            $quantity = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
            $price = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
            $summ = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
            $gramm = $worksheet->getCellByColumnAndRow(6, $row)->getValue();

            if (is_numeric($code)) {
                $insertqry = "INSERT INTO `products`(
                `code`, `title`, `type_price`, `quantity`, `price`, `summ`, `gram`)
                VALUES ('$code', '$title', '$type_price', '$quantity', '$price', '$summ', '$gramm');";
                $insertres = mysqli_query($con, $insertqry);
            }
        }

        $response['status'] = 'ok';
    }

    echo json_encode($response);
}

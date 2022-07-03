<?php

include_once 'database.php';

include_once __DIR__ . '/Classes/PHPExcel.php';
include_once __DIR__ . '/Classes/PHPExcel/Writer/Excel2007.php';

$query = "select * from products;";
$queryExecution = mysqli_query($con, $query);
$productList = mysqli_fetch_all($queryExecution, MYSQLI_ASSOC);

$xls = new PHPExcel();
$xls->setActiveSheetIndex(0);

$sheet = $xls->getActiveSheet();
$sheet->setTitle("price");
$sheet->getPageSetup()->SetPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

$border = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => '000000')
        ),
    )
);

$sheet->getStyle("A1:G" . count($productList))->applyFromArray($border);

$row = 1;
foreach ($productList as $product) {
    $sheet->setCellValue("A" . $row, $product['code']);
    $sheet->setCellValue("B" . $row, $product['title']);
    $sheet->setCellValue("C" . $row, $product['type_price']);
    $sheet->setCellValue("D" . $row, $product['quantity']);
    $sheet->setCellValue("E" . $row, $product['price']);
    $sheet->setCellValue("F" . $row, $product['summ']);
    $sheet->setCellValue("G" . $row, $product['gram']);
    $row++;
}

header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=file1996.xlsx");

$objWriter = new PHPExcel_Writer_Excel2007($xls);
$objWriter->save('php://output');
exit();

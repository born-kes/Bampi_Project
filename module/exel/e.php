<?php
/** Error reporting */
error_reporting(E_ALL);

/** Include path **/
ini_set('include_path', ini_get('include_path').';../Classes/');

/** PHPExcel */
include 'Classes/PHPExcel.php';

/** PHPExcel_Writer_Excel2007 */
include 'Classes/PHPExcel/Writer/Excel2007.php';
if(false){
// Create new PHPExcel object
//echo date('H:i:s') . " Create new PHPExcel object\n";
$objPHPExcel = new PHPExcel();

// Set properties
//echo date('H:i:s') . " Set properties\n";
$objPHPExcel->getProperties()->setCreator("Maarten Balliauw");
$objPHPExcel->getProperties()->setLastModifiedBy("Maarten Balliauw");
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");


// Add some data
//echo date('H:i:s') . " Add some data\n";
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Hello');
$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'world!');
$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Hello');
$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'world!');

// Rename sheet
//echo date('H:i:s') . " Rename sheet\n";
$objPHPExcel->getActiveSheet()->setTitle('Simple');
//echo '<br>'.PHPExcel_Worksheet::BREAK_COLUMN .'<br>';
$objPHPExcel->getActiveSheet()->setAutoFilter('E1:E'.PHPExcel_Worksheet::BREAK_COLUMN );

// Save Excel 2007 file
//echo date('H:i:s') . " Write to Excel2007 format\n";
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
// redirect output to client browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="myfile.xlsx"');
header('Cache-Control: max-age=0');

$objWriter->save('php://output');
//$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

// Echo done
//echo date('H:i:s') . " Done writing file.\r\n";
/*
$objPHPexcel = PHPExcel_IOFactory::load('Raport_07_05_AVW.xlsx');
$objWorksheet = $objPHPexcel->getActiveSheet();
var_dump($objWorksheet->getHeaderFooter());
var_dump($objWorksheet->getHeaderFooter()->getEvenFooter());
var_dump($objWorksheet->getHeaderFooter()->getOddHeader());
var_dump($objWorksheet->getHeaderFooter()->getEvenHeader());*/




}
else
{
//$objReader = PHPExcel_IOFactory::createReader("Excel2007");
//$objPHPExcel = $objReader->load("Raport_07_05_AVW.xlsx");
    $objPHPexcel = PHPExcel_IOFactory::load('Raport_AVW.xlsx');
    $objWorksheet = $objPHPexcel->getActiveSheet();
    $objWorksheet->getCell('A1')->setValue('John');
    $objWorksheet->getCell('A2')->setValue('Smith');

var_dump($objWorksheet->getCell('B2')->getValue());

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPexcel, 'Excel2007');


//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
//// redirect output to client browser
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//header('Content-Disposition: attachment;filename="Raport_07_05_AVW.xlsx"');
//header('Cache-Control: max-age=0');
//
//$objWriter->save('php://output');
}
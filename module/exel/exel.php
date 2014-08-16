<?php
/** Error reporting */
error_reporting(E_ALL);

// Przygotowania
function przebudowa($array){
    $efect=array();
    foreach($array as $row ){
        $efect[$row['kod_produktu']] = $row;
    }
    return $efect;
}
$this->loadInclude("module/sql/sql.php");
 $tbody =  sql('tbody_all');
 $thead = $tbody[0];$exel_licz=0;
$thead= array_map(function(){global $exel_licz; return ''.$exel_licz++;}, $thead);
//print_r($thead);
$thead=    array_flip($thead);


/** Include path **/
ini_set('include_path', ini_get('include_path').';../');

/** PHPExcel */
include 'PHPExcel.php';

/** PHPExcel_Writer_Excel2007 */
include 'PHPExcel/Writer/Excel2007.php';

$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator('Maarten Balliauw')
    ->setLastModifiedBy('Maarten Balliauw')
    ->setTitle('PHPExcel Test Document')
    ->setSubject('PHPExcel Test Document')
    ->setDescription('Test document for PHPExcel, generated using PHP classes.')
    ->setKeywords('office PHPExcel php')
    ->setCategory('Test result file');

/* Juz niewiem :P */
//$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

/* zcalenie komurek */
$objPHPExcel->getActiveSheet()->mergeCells('A1:N1');

/* Kolor Tła */
//$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
/* Koloroy Ramki
$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);
$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($styleThinBlackBorderOutline);

//Kolory tła inaczej
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->getStartColor()->setARGB('FF808080');
*/
/* Ramka i pogrubienie */
$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray(
		array(
			'font'    => array(
				'bold'      => true
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			),
			'borders' => array(
				'top'     => array(
 					'style' => PHPExcel_Style_Border::BORDER_THIN
 				)
			),
			'fill' => array(
	 			'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
	  			'rotation'   => 90,
	 			'startcolor' => array(
	 				'argb' => 'FFA0A0A0'
	 			),
	 			'endcolor'   => array(
	 				'argb' => 'FFFFFF00'
	 			)
	 		)
		)
);

$objPHPExcel->getActiveSheet()->getStyle('A2:O2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A2:O2')->getFill()->getStartColor()->setARGB('FFFFFF00');
$size = count($tbody)+2;/*($objPHPExcel->getActiveSheet()->getHighestRow())*/
$objPHPExcel->getActiveSheet()->getStyle('A3:A'.$size)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A3:A'.$size)->getFill()->getStartColor()->setARGB('FFFFC000');

$objPHPExcel->getActiveSheet()->getStyle('D3:E'.$size)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('D3:E'.$size)->getFill()->getStartColor()->setARGB('FFD7E4BC');

$objPHPExcel->getActiveSheet()->getStyle('H3:H'.$size)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('H3:H'.$size)->getFill()->getStartColor()->setARGB('FFFAF8E3');


/* FILTR */

$objPHPExcel->getActiveSheet()->setAutoFilter("C2:C".$size);

/* ZWIJANIE kolumny i rozwijanie */

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setOutlineLevel(1)
    ->setVisible(false)
    ->setCollapsed(false);

/* Szerokosci kolumn */
$objPHPExcel->getActiveSheet()->getColumnDimension()->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(false)->setWidth(16);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(33);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);




//$objPHPExcel->getActiveSheet()->freezePane('A3');

//$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);


/* WSTAWIANIE DUZO TRESCI NP ARRAY */
$objPHPExcel->getActiveSheet()->fromArray($thead, NULL, 'A2');
$objPHPExcel->getActiveSheet()->fromArray($tbody, NULL, 'A3');

/* Formatowanie na procenty % */
//$objPHPExcel->getActiveSheet()->getStyle('A1:A8')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
/* Formatowanie data PHPExcel_Shared_Date::PHPToExcel( gmmktime(0,0,0,date('m'),date('d'),date('Y')) */
//$objPHPExcel->getActiveSheet()->getStyle('D1')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);

/* Formatowanie na Euro */
//$objPHPExcel->getActiveSheet()->getStyle('E4:E13')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);

$objPHPExcel->getActiveSheet()->getStyle('D2:E'.$size)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_PLN);

$objPHPExcel->getActiveSheet()->getStyle('DH:O'.$size)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_PLN);

/* Równaj do prawej */
//$objPHPExcel->getActiveSheet()->getStyle('D13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

/* Generowanie przypisów - komentarzy
$objCommentRichText = $objPHPExcel->getActiveSheet()->getComment('E11')->getText()->createTextRun('PHPExcel:');
$objCommentRichText->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getComment('E11')->getText()->createTextRun("\r\n");
//*/

/* Obliczenia */
//$objPHPExcel->getActiveSheet()->setCellValue('B3', '=D5 & " " & D7');
//$objPHPExcel->getActiveSheet()->setCellValue('B4', '=D5+D7');

/* ZEROWANIE NA KONIEC? */
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Raport_'.( date("d_m") ).'_AVW.xlsx"');
header('Cache-Control: max-age=0');

$objWriter->save('php://output'); //*/
exit;
return array();
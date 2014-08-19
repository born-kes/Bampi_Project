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

/**
 * Dostęp do elementów
 */
$objWorksheet = $objPHPExcel->getActiveSheet();

/* Juz niewiem :P */
//$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

/* zcalenie komurek */
$objWorksheet->mergeCells('A1:N1');

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
$objWorksheet->getStyle('A1:O1')->applyFromArray(
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

$objWorksheet->getStyle('A2:O2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objWorksheet->getStyle('A2:O2')->getFill()->getStartColor()->setARGB('FFFFFF00');
$size = count($tbody)+2;/*($objPHPExcel->getActiveSheet()->getHighestRow())*/
$objWorksheet->getStyle('A3:A'.$size)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objWorksheet->getStyle('A3:A'.$size)->getFill()->getStartColor()->setARGB('FFFFC000');

$objWorksheet->getStyle('D3:E'.$size)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objWorksheet->getStyle('D3:E'.$size)->getFill()->getStartColor()->setARGB('FFD7E4BC');

$objWorksheet->getStyle('H3:H'.$size)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objWorksheet->getStyle('H3:H'.$size)->getFill()->getStartColor()->setARGB('FFFAF8E3');


/* FILTR */

$objWorksheet->setAutoFilter("C2:C".$size);

/* ZWIJANIE kolumny i rozwijanie */

$objWorksheet->getColumnDimension('A')->setOutlineLevel(1)
    ->setVisible(false)
    ->setCollapsed(false);

/* Szerokosci kolumn */
$objWorksheet->getColumnDimension()->setAutoSize(true)->setWidth(10);
$objWorksheet->getColumnDimension('A')->setAutoSize(false)->setWidth(16);
$objWorksheet->getColumnDimension('B')->setWidth(33);
$objWorksheet->getColumnDimension('C')->setAutoSize(true);
$objWorksheet->getColumnDimension('D')->setWidth(11);
$objWorksheet->getColumnDimension('E')->setWidth(11);
$objWorksheet->getColumnDimension('F')->setWidth(33);
$objWorksheet->getColumnDimension('G')->setVisible(false);
$objWorksheet->getColumnDimension('H')->setWidth(11);
$objWorksheet->getColumnDimension('I')->setWidth(11);
$objWorksheet->getColumnDimension('J')->setWidth(11);
$objWorksheet->getColumnDimension('K')->setWidth(11);
$objWorksheet->getColumnDimension('L')->setWidth(11);
$objWorksheet->getColumnDimension('M')->setWidth(11);
$objWorksheet->getColumnDimension('N')->setWidth(11);
$objWorksheet->getColumnDimension('O')->setWidth(11);




//$objWorksheet->freezePane('A3');

//$objWorksheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);


/* WSTAWIANIE DUZO TRESCI NP ARRAY */
$objWorksheet->fromArray($thead, NULL, 'A2');
$objWorksheet->fromArray($tbody, NULL, 'A3');

/* Formatowanie na procenty % */
//$objWorksheet->getStyle('A1:A8')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
/* Formatowanie data PHPExcel_Shared_Date::PHPToExcel( gmmktime(0,0,0,date('m'),date('d'),date('Y')) */
//$objWorksheet->getStyle('D1')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);

/* Formatowanie na Euro */
//$objWorksheet->getStyle('E4:E13')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);

$objWorksheet->getStyle('D2:E'.$size)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_PLN);

$objWorksheet->getStyle('H3:O'.$size)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_PLN);

/* Równaj do prawej */
//$objWorksheet->getStyle('D13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

/* Generowanie przypisów - komentarzy
$objCommentRichText = $objWorksheet->getComment('E11')->getText()->createTextRun('PHPExcel:');
$objCommentRichText->getFont()->setBold(true);
$objWorksheet->getComment('E11')->getText()->createTextRun("\r\n");
//*/

for($i=3;$i<$size;$i++){
    if( $objWorksheet->getCell('H'.$i)->getValue() == 'Av World')
    {
    $objWorksheet->getStyle('H'.$i)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_DARKGREEN);
    }
    else
    {$orange = 'FFFF8C00';
        if(  $objWorksheet->getCell('J'.$i)->getValue() == 'Av World' ){
            $objWorksheet->getStyle('J'.$i)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_ORANGE);
        }
        else if(  $objWorksheet->getCell('L'.$i)->getValue() == 'Av World' ){
            $objWorksheet->getStyle('L'.$i)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_PING);
        }
        else if(  $objWorksheet->getCell('N'.$i)->getValue() == 'Av World' ){
            $objWorksheet->getStyle('N'.$i)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
        }


        $objWorksheet->getStyle('H'.$i)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    }

}

/* Obliczenia */
//$objWorksheet->setCellValue('B3', '=D5 & " " & D7');
//$objWorksheet->setCellValue('B4', '=D5+D7');

/* ZEROWANIE NA KONIEC? */
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Raport_'.( date("d_m") ).'_AVW.xlsx"');
header('Cache-Control: max-age=0');

$objWriter->save('php://output'); //*/
exit;
return array();
<?php
/** Error reporting */
error_reporting(E_ALL);
function alfabet($nr=null){
    global $thead;

    if(is_numeric($nr)){

    }elseif(is_string($nr)){
        $nr =  array_search( $nr, $thead );
    }

    if(is_null($nr) || (is_bool($nr) && !$nr ) ){return $nr;}

    $alfabet = range('A', 'Z');
    /** litery zaczynają się od 1 */
    return $alfabet[$nr];


}
// Przygotowania
function przebudowa($array){
    $efect=array();
    foreach($array as $row ){
        $efect[$row['kod_produktu']] = $row;
    }
    return $efect;
}
/** Pobranie treści */
global $thead;
$this->loadInclude("module/sql/sql.php");
$tbody =  sql('tbody_all');
$thead = $tbody[0];

$exel_licz=0;
$thead= array_map(function(){global $exel_licz; return ''.$exel_licz++;}, $thead);

$thead=    listEl(array_flip($thead),0);


/** Include path **/
ini_set('include_path', ini_get('include_path').';../');

/** PHPExcel */
include 'PHPExcel.php';

/** PHPExcel_Writer_Excel2007 */
include 'PHPExcel/Writer/Excel2007.php';

$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()
    ->setCreator('')
    ->setLastModifiedBy('')
    ->setTitle('')
    ->setSubject('  ')
    ->setDescription('')
    ->setKeywords('')
    ->setCategory('');

/**
 * Dostęp do elementów
 */
$objWorksheet = $objPHPExcel->getActiveSheet();

/* Juz niewiem :P */
//$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);

/* zcalenie komurek */
$sizeHead = count($thead)-1;
$objWorksheet->mergeCells('A1:'.alfabet($sizeHead).'1');



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

//Kolory tła inaczej */
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);

$objPHPExcel->getActiveSheet()->getStyle('A1:'.alfabet($sizeHead).'1')->getFill()->getStartColor()->setARGB('FF808080');

/* Ramka i pogrubienie */
$objWorksheet->getStyle('A1:'.alfabet($sizeHead).'1')->applyFromArray(
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

$objWorksheet->getStyle('A2:'.alfabet( $sizeHead ).'2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objWorksheet->getStyle('A2:'.alfabet( $sizeHead ).'2')->getFill()->getStartColor()->setARGB('FFFFFF00');

$size = count($tbody)+2;/*($objPHPExcel->getActiveSheet()->getHighestRow())*/
$objWorksheet->getStyle('A3:A'.$size)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objWorksheet->getStyle('A3:A'.$size)->getFill()->getStartColor()->setARGB('FFFFC000');



$objWorksheet->getStyle('H3:H'.$size)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objWorksheet->getStyle('H3:H'.$size)->getFill()->getStartColor()->setARGB('FFFAF8E3');


/* FILTR */
if(alfabet( 'kod_produktu' )!==false){

//    ukrywanie
    $objWorksheet->getColumnDimension(alfabet( 'kod_produktu' ))->setOutlineLevel(1)
        ->setVisible(false)
        ->setCollapsed(false);

}
if(alfabet( 'symbol' )!==false){
     $objWorksheet->getColumnDimension(alfabet( 'kod_produktu' ))->setOutlineLevel(1)
        ->setVisible(false)
        ->setCollapsed(false);

}

/* ZWIJANIE kolumny i rozwijanie */




/* Szerokosci kolumn */
//echo  $thead[$i].' : '.$i.' = '.alfabet($i).BR;
for($i =0;$i<$sizeHead;$i++ ){
    if($thead[$i]=='kod_produktu' || $thead[$i]=='symbol')
    {
        $objWorksheet->getColumnDimension( alfabet($i) )->setAutoSize(false)->setWidth(16);
    }
    elseif( $thead[$i]=='nazwa' )
    {
        $objWorksheet->getColumnDimension( alfabet($i) )->setAutoSize(true)->setWidth(27);
    }
    elseif( $thead[$i]=='id' )
    {
        $objWorksheet->getColumnDimension( alfabet($i) )->setVisible(false)->setCollapsed(false);
    }
    elseif( $thead[$i]=='notatka' )
    {
        $objWorksheet->getColumnDimension( alfabet($i) )->setWidth(33);
    }
    elseif(  strpos($thead[$i],'cena')!==false )
    {
        $objWorksheet->getColumnDimension( alfabet($i) )->setWidth(12);

        $objWorksheet->getStyle(alfabet($i).'2:'.alfabet($i).$size)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_PLN);

        $objWorksheet->getStyle(alfabet($i).'3:'.alfabet($i).$size)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objWorksheet->getStyle(alfabet($i).'3:'.alfabet($i).$size)->getFill()->getStartColor()->setARGB('FFD7E4BC');
      //  $objWorksheet->getColumnDimension( alfabet($i) )->setWidth(33);
    }
    else
    {
        $objWorksheet->getColumnDimension(alfabet($i))->setAutoSize(false)->setWidth(12);
    }
    if( $thead[$i]=='producent' || $thead[$i]=='notatka'){
        $objWorksheet->setAutoFilter(alfabet( $i )."2:".alfabet($i).$size);
    }
}


$objWorksheet->freezePane('B3');

$objWorksheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);


/* WSTAWIANIE DUZO TRESCI NP ARRAY */
$objWorksheet->fromArray($thead, NULL, 'A2');
$objWorksheet->fromArray($tbody, NULL, 'A3');

/* Formatowanie na procenty % */
//$objWorksheet->getStyle('A1:A8')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
/* Formatowanie data PHPExcel_Shared_Date::PHPToExcel( gmmktime(0,0,0,date('m'),date('d'),date('Y')) */
//$objWorksheet->getStyle('D1')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_XLSX15);

/* Równaj do prawej */
//$objWorksheet->getStyle('D13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

/* Generowanie przypisów - komentarzy
$objCommentRichText = $objWorksheet->getComment('E11')->getText()->createTextRun('PHPExcel:');
$objCommentRichText->getFont()->setBold(true);
$objWorksheet->getComment('E11')->getText()->createTextRun("\r\n");
//*/

for($i=3;$i<$size;$i++){
    if( $objWorksheet->getCell(alfabet('top1').$i)->getValue() == 'Av World')
    {
        $objWorksheet->getStyle(alfabet('top1').$i)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_DARKGREEN);
    }
    else
    {
        if(  $objWorksheet->getCell(alfabet('top2').$i)->getValue() == 'Av World' ){
            $objWorksheet->getStyle(alfabet('top1').$i)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_ORANGE);
        }
        else if(  $objWorksheet->getCell(alfabet('top3').$i)->getValue() == 'Av World' ){
            $objWorksheet->getStyle(alfabet('top1').$i)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_PING);
        }
        else if(  $objWorksheet->getCell(alfabet('top4').$i)->getValue() == 'Av World' ){
            $objWorksheet->getStyle(alfabet('top4').$i)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
        }


        $objWorksheet->getStyle('H'.$i)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    }

}
$objWorksheet->freezePane('A3');

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
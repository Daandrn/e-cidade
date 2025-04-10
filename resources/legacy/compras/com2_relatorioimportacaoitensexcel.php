<?php
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_libsys.php");
require_once("std/db_stdClass.php");
require_once("classes/db_pcorcam_classe.php");
include("libs/PHPExcel/Classes/PHPExcel.php");

$styleItens1 = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
    'font' => array(
        'size' => 9,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),
);

$styleItens2 = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
            'rgb' => '00f703'
        )
    ),
    'font' => array(
        'size' => 10,
        'bold' => true,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),
);

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();




// Create a first sheet, representing sales data
$objPHPExcel->setActiveSheetIndex(0);
$sheet = $objPHPExcel->getActiveSheet();
$sheet->setCellValue('A1', 'Ordem');
$sheet->setCellValue('B1', 'Cod Material');
$sheet->setCellValue('C1', 'Reduzido do Desdobramento');
$sheet->setCellValue('D1', 'Controlado por Quantidade');
$sheet->setCellValue('E1', 'Quantidade');
$sheet->setCellValue('F1', 'Cod. Unidade');


$sheet->getStyle('A1:F1')->applyFromArray($styleItens2);


$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);

$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
$objPHPExcel->getActiveSheet()->protectCells('A1:F1', 'PHPExcel');
$objPHPExcel->getActiveSheet()
    ->getStyle('A2:F2000')
    ->getProtection()->setLocked(
        PHPExcel_Style_Protection::PROTECTION_UNPROTECTED
    );

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Dados do item');

// Create a new worksheet, after the default sheet
$objPHPExcel->createSheet();

// Add some data to the second sheet, resembling some different data types
$objPHPExcel->setActiveSheetIndex(1);
$sheet = $objPHPExcel->getActiveSheet();
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Cod. Unidade');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Descricao');

$sheet->getStyle('A1:B1')->applyFromArray($styleItens2);


$result = db_query("select * from matunid order by m61_descr");
if (pg_numrows($result) != 0) {
    $numrows = pg_numrows($result);
    $numcell = 1;
    for ($i = 0; $i < $numrows; $i++) {

        $celulaA = "A" . ($numcell + 1);
        $celulaB = "B" . ($numcell + 1);

        $matunid = db_utils::fieldsMemory($result, $i);
        $sheet->setCellValue($celulaA, $matunid->m61_codmatunid);
        $sheet->setCellValue($celulaB, $matunid->m61_descr);
        $numcell++;
    }
}


$sheet->getStyle('A1:B1000')->applyFromArray($styleItens1);


$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);

$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
//$objPHPExcel->getActiveSheet()->protectCells('A1:B1', 'PHPExcel');


// Rename 2nd sheet
$objPHPExcel->getActiveSheet()->setTitle('Unidades de medida');

$objPHPExcel->setActiveSheetIndex(0);
$sheet = $objPHPExcel->getActiveSheet();


$result = db_query("select * from pcmater inner join pcmaterele on pc01_codmater = pc07_codmater
where pc01_codmater in ((select pc96_codmater from importacaoitens  where pc96_sequencial= $codigoimportacao));");
if (pg_numrows($result) != 0) {
    $numrows = pg_numrows($result);
    $numcell = 1;
    for ($i = 0; $i < $numrows; $i++) {

        $celulaA = "A" . ($numcell + 1);
        $celulaB = "B" . ($numcell + 1);
        $celulaC = "C" . ($numcell + 1);


        $rsPcmater = db_utils::fieldsMemory($result, $i);
        $sheet->setCellValue($celulaA, $i + 1);
        $sheet->setCellValue($celulaB, $rsPcmater->pc01_codmater);
        $sheet->setCellValue($celulaC, $rsPcmater->pc07_codele);

        $numcell++;
    }
}


header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=planilha_importacao_$codigoimportacao.xlsx");
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

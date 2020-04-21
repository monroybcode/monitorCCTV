<?php

session_start();
require_once './resources/class/PHPExcel.php';
require './resources/connection/conexion.php';
$mysqli->query("SET NAMES 'UTF8'");

$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()
        ->setTitle("Reporte " . $_SESSION['nr'] . " " . date("Y/m/d"))
        ->setSubject("Excel")
        ->setCategory("Documento");



$sql = $_SESSION['sql_reporte_resumen'];

$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("A1", "Reporte")
        ->setCellValue("B1", "Estatus")
        ->setCellValue("C1", "Categoria")
        ->setCellValue("D1", "Area")
        ->setCellValue("E1", "Hospital")
        ->setCellValue("F1", "Solicitante")
        ->setCellValue("G1", "Asignado")
        ->setCellValue("H1", "Prioridad")
        ->setCellValue("I1", "Fecha Solicitud")
        ->setCellValue("J1", "Fecha Resolucion")
        ->setCellValue("K1", "Fecha Cierre");

$cont = "2";

$resultado = $mysqli->query($sql);
while ($fila = $resultado->fetch_assoc()) {
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $cont, $fila['id_ticket']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $cont, $fila['estatus']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $cont, $fila['categoria']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $cont, $fila['nombre_area']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $cont, $fila['hospital']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $cont, $fila['puesto']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G" . $cont, $fila['asignado']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H" . $cont, $fila['prioridad']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I" . $cont, $fila['fecha_registro']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J" . $cont, $fila['fecha_resolucion']);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K" . $cont, $fila['fecha_cierre']);

    $cont++;
}



$objPHPExcel->getActiveSheet()->setTitle(date("d-m-Y"));
$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte ' . $_SESSION['nr'] . ' ' . date("Y/m/d") . '.xlsx"');
header('Cache-Control: max-age = 0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

exit;
?>


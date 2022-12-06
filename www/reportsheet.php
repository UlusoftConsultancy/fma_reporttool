<?php

    // convert report to sheet
    require 'vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;

    require 'db_connection.php';

    $query = 'SELECT * FROM apk_dmu_rapportage';
    $result = $mysqli->query($query);

    $data = array();
    $rows = 0;
    while ($row = $result->fetch_assoc())
    {
        $data[$rows][] = $row['fk_fma_excel'];
        $data[$rows][] = date("d/m/y", $row['unixdate']);
        $data[$rows][] = $row['ordernummer'];
        $data[$rows][] = $row['status_excel'] == 1 ? 'CORRECT' : 'ONTBREEKT';
        $rows++;
    }

    \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder( new \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder() );
    $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();

    $sheet = $spreadsheet->getSheet(0);
    $sheet->fromArray(['Excel Rij Fma', 'Datum', 'ordernummer', 'status']);
    $sheet->fromArray($data, null, 'A2');

    $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('assets/reports/raport_apk_dmu.xlsx');
?>
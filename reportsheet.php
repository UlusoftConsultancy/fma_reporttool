<?php

    // convert report to sheet
    require 'vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;

    if (isset($_POST['data']))
    {
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder( new \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder() );
        $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet = $spreadsheet->getSheet(0);
        $sheet->fromArray(['Excel key Apk', 'Excel key Fma', 'Datum', 'Oder nr.', 'Status']);
        $sheet->fromArray(json_decode($_POST['data'], true), null, 'A2');

        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('assets/reports/raport_apk_dmu.xlsx');
    }

?>
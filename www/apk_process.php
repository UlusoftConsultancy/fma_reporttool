<?php

    require 'vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;

    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $spreadsheet = $reader->load('assets/excels/apk/' . $_POST['apkData']);
    $worksheet = $spreadsheet->getSheetByName("Detail uitvoering");
    if ($worksheet == null)
    {
        $worksheet = $spreadsheet->getSheetByName("Detail uitvoering | backlog");
    }
    $sheetData = $worksheet->toArray(null, false);

    // Get the highest row and column numbers referenced in the worksheet
    $highestRow = $worksheet->getHighestRow(); // e.g. 10
    $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
    $highestColumnIndex = PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

    // full data
    $masterData = array('date' => array(), 'ordernr' => array(), 'fk_apk' => array(), 'teaplus' => array());

    // extract date fields
    for ($row = 1; $row <= $highestRow; $row++) 
    {
        for ($col = 1; $col <= $highestColumnIndex; $col++) 
        {
            if ($worksheet->getCellByColumnAndRow($col, $row)->getValue() == 'Datum  terugmelding')
            {
                $masterData['date'] = array_slice(array_column($sheetData, $col - 1), $row);
                $masterData['ordernr'] = array_slice(array_column($sheetData, $col), $row);
                $masterData['fk_apk'] = range($row + 1, $highestRow);
                break 2; // exit out of nested loop when found
            }
        }
    }

    // extract TEAPlus fields
    for ($row = 1; $row <= $highestRow; $row++) 
    {
        for ($col = 1; $col <= $highestColumnIndex; $col++) 
        {
            if ($worksheet->getCellByColumnAndRow($col, $row)->getValue() == 'Opmerking TEAPlus')
            {
                $masterData['teaplus'] = array_slice(array_column($sheetData, $col - 1), $row);
                break 2; // exit out of nested loop when found
            }
        }
    }

    echo json_encode($masterData);    

?>
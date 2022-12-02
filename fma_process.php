<?php

// load db_connection file
require 'db_connection.php';
// load fma report and return table
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

$spreadsheet = $reader->load('assets/excels/fma/' . $_POST['fmaData'][0]);
$worksheet = $spreadsheet->getSheetByName("Alle Prestaties");
$sheetData = $worksheet->toArray(null, false, false);

// Get the highest row and column numbers referenced in the worksheet
$highestRow = $worksheet->getHighestRow(); // e.g. 10
$highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
$highestColumnIndex = PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

// full data
$masterData = array('date' => array(), 'ordernr' => array(), 'fk_fma' => array());

// extract date fields
for ($row = 1; $row <= $highestRow; $row++) 
{
    for ($col = 1; $col <= $highestColumnIndex; $col++) 
    {
        if ($worksheet->getCellByColumnAndRow($col, $row)->getValue() == 'Datum')
        {
            $masterData['date'] = array_slice(array_column($sheetData, $col - 1), $row);
            // format date msexcel format to unix format
            $masterData['date'] = array_map(function($input) { return intval(($input- 25569) * 86400); }, $masterData['date']);
            // get other columns
            $masterData['ordernr'] = array_slice(array_column($sheetData, $col), $row);
            $masterData['fk_fma'] = range($row + 1, $highestRow);
            // exit out of nested loop when found
            break 2; 
        }
    }
}

for ($index = 0; $index < count($masterData['fk_fma']); $index++)
{
    $query = 'INSERT IGNORE INTO apk_dmu_rapportage (fk_fma_excel, unixdate, ordernummer, beschrijving, status) 
              VALUES (' . $masterData['fk_fma'][$index] . ', ' . $masterData['date'][$index] . ', "' . $masterData['ordernr'][$index] . '", "",' . 1 . ')';
    $mysqli->query($query);
}

// maintenance of database
// delete all rows where ordernr is empty
$query = 'DELETE FROM apk_dmu_rapportage WHERE ordernummer = ""';
$mysqli->query($query);

echo json_encode($masterData);
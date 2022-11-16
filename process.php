<?php

    require 'vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;

    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

    $spreadsheetFma = $reader->load('assets/' . $_POST['fmaData']);
    $spreadsheetApk = array();
    
    // load all the apk data files
    $spreadsheetApkData = $_POST['apkData']; // filenames
    for ($index = 0; $index < count($spreadsheetApkData); $index++)
        $spreadsheetApk[] = $reader->load('assets/' . $spreadsheetApkData[$index]);
    
    // load dmu sheet from fma
    $sheetFma = $spreadsheetFma->getSheetByName('fma')->toArray();

    // load dmu sheets from apk files
    $sheetApk = array(); 
    for ($index = 0; $index < count($spreadsheetApk); $index++)
    {
        $sheetApk[] = $spreadsheetApk[$index]->getSheetByName('Detail uitvoering')->toArray();
        array_shift($sheetApk[$index]);
    }

    // filter dates in apk dmu
    $datesApk = array();
    for ($index = 0; $index < count($sheetApk); $index++)
    {
        $datesApk[$index] = array_column($sheetApk[$index], 0);
        // $datesApk[$index] = array_filter($datesApk[$index]);    
    }

    // min and max date
    $mindateRowIndex = 0;
    $mindateColumnIndex = 0;
    $maxdateRowIndex = 0;
    $maxdateColumnIndex = 0;

    for ($rowIndex = 0; $rowIndex < count($datesApk); $rowIndex++)
    {
        for ($columnIndex = 0; $columnIndex < count($datesApk[$rowIndex]); $columnIndex++)
        {
            if ($datesApk[$rowIndex][$columnIndex] != null && strtotime($datesApk[$rowIndex][$columnIndex]) < strtotime($datesApk[$mindateRowIndex][$mindateColumnIndex]))
            {
                $mindateRowIndex = $rowIndex;
                $mindateColumnIndex = $columnIndex;
            }

            if ($datesApk[$rowIndex][$columnIndex] != null && strtotime($datesApk[$rowIndex][$columnIndex]) > strtotime($datesApk[$maxdateRowIndex][$maxdateColumnIndex]))
            {
                $maxdateRowIndex = $rowIndex;
                $maxdateColumnIndex = $columnIndex;
            }
        }
    }

    // filter dates in fma dmu
    $datesFma = array_column($sheetFma, 3);

    // min date index in fma dmu
    $minDateIndexFma = 0;
    for ($index = 0; $index < count($datesFma); $index++)
    {
        if ($datesFma[$index] && strtotime($datesFma[$index]) >= strtotime($datesApk[$mindateRowIndex][$mindateColumnIndex]))
        {
            $minDateIndexFma = $index; // from this index on, we process the report
            break; // end the search
        }
    }

    // max date index in fma dmu
    $maxDateIndexFma = $minDateIndexFma;
    for ($index = $minDateIndexFma; $index < count($datesFma); $index++)
    {
        if ($datesFma[$index] && strtotime($datesFma[$index]) > strtotime($datesApk[$maxdateRowIndex][$maxdateColumnIndex]))
        {
            $maxDateIndexFma = $index - 1;
            break; // end the search
        }
    }

    // filter orders in fma sheet based on dates given in apk sheet
    $ordersApk = array();
    for ($index = 0; $index < count($sheetApk); $index++)
        $ordersApk[] = array_column($sheetApk[$index], 1);

    $ordersFma = array_column($sheetFma, 4);
    $filteredOrdersFma = array_slice($ordersFma, $minDateIndexFma, $maxDateIndexFma - $minDateIndexFma + 1);
    
    // search order numbers from fma in apk sheet
    $report = array();
    for ($index = 0; $index < count($filteredOrdersFma); $index++)
    {
        for ($apkIndex = 0; $apkIndex < count($ordersApk); $apkIndex++)
        {
            $foundInApk = array_search($filteredOrdersFma[$index], $ordersApk[$apkIndex]);
            if ($foundInApk !== false)
            {
                $report[] = array('keyApk' => $foundInApk, 'nameApk' => $spreadsheetApkData[$apkIndex], 'keyFma' => $minDateIndexFma + $index, 'date' => $datesFma[$minDateIndexFma + $index], 'value' => strval($ordersApk[$apkIndex][$foundInApk]), 'status' => 'CORRECT');
                break; // stop searching, we already found
            }
            else
            {
                // if we are at the last index
                if ($apkIndex == count($ordersApk) - 1)
                    $report[] = array('keyApk' => -1, 'nameApk' => '-', 'keyFma' => $minDateIndexFma + $index, 'date' => $datesFma[$minDateIndexFma + $index], 'value' => strval($filteredOrdersFma[$index]), 'status' => 'ONTBREEKT');
            }
        }
    }

    echo json_encode($report);

?>
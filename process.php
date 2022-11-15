<?php

    require 'vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;

    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $spreadsheetFma = $reader->load('assets/tmpFma.xlsx');
    $spreadsheetApk = $reader->load('assets/tmpApk.xlsx');
    
    // load dmu sheet from fma
    $sheetFma = $spreadsheetFma->getSheetByName('fma')->toArray();

    // load dmu sheet from apk
    $sheetApk = $spreadsheetApk->getSheetByName('Detail uitvoering')->toArray();

    // filter dates in apk dmu
    $datesApk = array_column($sheetApk, 0);

    // min date
    $minDateIndex = 0;
    for ($index = 0; $index < count($datesApk); $index++)
    {
        if ($datesApk[$index] && strtotime($datesApk[$index]) < strtotime($datesApk[$minDateIndex]))
            $minDateIndex = $index;
    }

    // max date
    $maxDateIndex = 0;
    for ($index = 0; $index < count($datesApk); $index++)
    {
        if ($datesApk[$index] && strtotime($datesApk[$index]) > strtotime($datesApk[$maxDateIndex]))
            $maxDateIndex = $index;
    }

    // filter dates in fma dmu
    $datesFma = array_column($sheetFma, 3);

    // min date index in fma dmu
    $minDateIndexFma = 0;
    for ($index = 0; $index < count($datesFma); $index++)
    {
        if ($datesFma[$index] && strtotime($datesFma[$index]) >= strtotime($datesApk[$minDateIndex]))
        {
            $minDateIndexFma = $index; // from this index on, we process the report
            break; // end the search
        }
    }

    // max date index in fma dmu
    $maxDateIndexFma = $minDateIndexFma;
    for ($index = $minDateIndexFma; $index < count($datesFma); $index++)
    {
        if ($datesFma[$index] && strtotime($datesFma[$index]) > strtotime($datesApk[$maxDateIndex]))
        {
            $maxDateIndexFma = $index;
            break; // end the search
        }
    }

    // filter orders in fma sheet based on dates given in apk sheet
    $ordersApk = array_column($sheetApk, 1);
    $ordersFma = array_column($sheetFma, 4);
    $filteredOrdersFma = array_slice($ordersFma, $minDateIndexFma, $maxDateIndexFma - $minDateIndexFma);
    
    // search order numbers from fma in apk sheet
    $report = array();
    for ($index = 0; $index < count($filteredOrdersFma); $index++)
    {
        $foundInApk = array_search($filteredOrdersFma[$index], $ordersApk);
        if ($foundInApk !== false)
        {
            $report[] = array('keyApk' => $foundInApk, 'keyFma' => $minDateIndexFma + $index, 'date' => $datesFma[$minDateIndexFma + $index], 'value' => $ordersApk[$foundInApk], 'status' => 'CORRECT');
        }
        else
        {
            $report[] = array('keyApk' => -1, 'keyFma' => $minDateIndexFma + $index, 'date' => $datesFma[$minDateIndexFma + $index], 'value' => $filteredOrdersFma[$index], 'status' => 'ONTBREEKT');
        }
    }

    echo json_encode($report);

?>
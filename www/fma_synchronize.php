<?php

// load db_connection file
require 'db_connection.php';

$query = 'SELECT * FROM apk_dmu_rapportage ORDER BY fk_fma_excel ASC';
$result = $mysqli->query($query);

// full data
$masterData = array('date' => array(), 'ordernr' => array(), 'fk_fma' => array(), 'status_excel' => array(), 'beschrijving' => array(), 'status' => array());
while ($row = $result->fetch_assoc())
{
    $masterData['date'][] = $row['unixdate'];
    $masterData['ordernr'][] = $row['ordernummer'];
    $masterData['fk_fma'][] = $row['fk_fma_excel'];
    $masterData['beschrijving'][] = $row['beschrijving'];
    $masterData['status_excel'][] = $row['status_excel'];
    $masterData['status'][] = $row['status'];
}

echo json_encode($masterData);
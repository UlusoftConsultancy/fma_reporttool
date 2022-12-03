<?php

require 'db_connection.php';

$query = 'UPDATE apk_dmu_rapportage SET status=' . $_POST['status'] . ', beschrijving="' . $_POST['description'] . '" WHERE fk_fma_excel="' . $_POST['fma_key'] . '"';
$result = $mysqli->query($query);

if ($result)
{

}
else
{
    echo $mysqli->error;
}
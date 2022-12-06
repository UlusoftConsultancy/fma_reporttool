<?php

require 'db_connection.php';

$query = 'UPDATE apk_dmu_rapportage SET status_excel=' . $_POST['status_excel'] . ' WHERE ordernummer="' . $_POST['ordernr'] . '"';
$result = $mysqli->query($query);

if ($result)
{
    
}
else
{
    echo "Error apk_update.php:: " . $mysqli->error;
}
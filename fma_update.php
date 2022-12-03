<?php

require 'db_connection.php';

$query = 'UPDATE apk_dmu_rapportage SET status=' . $_POST['status'] . ', beschrijving="' . $_POST['description'] . '" WHERE ordernummer="' . $_POST['order'] . '"';
$result = $mysqli->query($query);

echo $result;
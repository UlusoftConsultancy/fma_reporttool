<?php

require 'db_connection.php';

$query = 'UPDATE apk_dmu_rapportage SET status_excel=' . $_POST['status_excel'] . ' WHERE ordernummer="' . $_POST['ordernr'] . '"';
$mysqli->query($query);
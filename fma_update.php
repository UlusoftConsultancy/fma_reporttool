<?php

require 'db_connection.php';

$query = 'UPDATE apk_dmu_rapportage SET status=' . $_POST['status'] . ' WHERE ordernummer="' . $_POST['order'] . '"';
$mysqli->query($query);
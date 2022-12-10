<?php
    session_start();
    $_SESSION['logged_in'] = 0;
    if ($_POST['username'] == 'admin' && $_POST['password'] == 'admin123456789')
        $_SESSION['logged_in'] = 1;

    echo $_SESSION['logged_in'];
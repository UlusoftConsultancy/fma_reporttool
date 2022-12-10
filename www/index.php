<?php
    session_start();
    if (!$_SESSION['logged_in']) { header('Location: login.php'); }    
?>
<html>
    <head>
        <title>FMA - APK DMU RAPORT | HOME</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>

    <body>

        <nav class="navbar has-background-light" role="navigation" aria-label="main navigation">
            <div class="navbar-brand">
                <a class="navbar-item" href="#">
                <img src="assets/png/logo.png">
                </a>

                <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                </a>
            </div>

            <div id="navbarBasicExample" class="navbar-menu">
                <div class="navbar-start">
                    <a class="navbar-item">
                        APK - DMU Raportage
                    </a>
                </div>
            </div>
        </nav>

        <section class="section">
            <h1 class="title">Overzicht Excel Files<img id="excel-loader" src="assets/png/loader.gif" style="width:25px;margin:0 0 0 1em;"/></h1>
            <h2 class="subtitle">Alle excel files die onder de /assets/excels folder gevonden zijn.</h2>
            <div id="fma-dmu-tagbox" class="box">
                <h4 class="title is-4">Excel files Fma</h4>
                <span id="fma-dmu-tag" class="tag is-danger is-light">Geen excels gevonden</span>
            </div>
            <div id="apk-dmu-tagbox" class="box">
                <h4 class="title is-4">Excel files Apk</h4>
                <span id="apk-dmu-tag" class="tag is-danger is-light">Geen excels gevonden</span>
            </div>
            <div id="apkId-dmu-tagbox" class="box">
                <h4 class="title is-4">Excel files indienstelling</h4>
                <span id="apkId-dmu-tag" class="tag is-danger is-light">Geen excels gevonden</span>
            </div>
        </section>

        <section id="report-section" class="section" style="visibility: hidden;">
            <h1 class="title" style="display:flex;align-items:center;">
                Rapport
                <img id="report-loader" src="assets/png/loader.gif" style="width:25px;margin:0 0.5em 0 1em;visibility:hidden;"/>
                <span id="report-status" class="tag is-warning" style="visibility:hidden;"></span>
            </h1>
            <h2 class="subtitle">Order nummers worden vergeleken en geraporteerd</h2>
            <button id="button-synchronize-report" class="button is-light">Synchroniseer rapport</button>
            <button id="button-download-report" class="button is-light">Download raport</button>
            <div class="content m-1">
                <table>
                    <thead>
                        <tr>
                            <th>Excel key Fma</th>
                            <th>Datum</th>
                            <th>Order nr.</th>
                            <th>Status Excel</th>
                            <th>Status Opvolging</th>
                            <th>Beschrijving</th>
                            <th>Actie</th>
                        </tr>
                    </thead>
                    <tbody id="table-report-body">

                    </tbody>
                </table>
            </div>
        </section>

    </body>

    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script src="main.js" type="module"></script>
</html>
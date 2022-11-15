<html>
    <head>
        <title>FMA - APK DMU RAPORT</title>
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
            <h1 class="title">Upload Excels</h1>
            <h2 class="subtitle">Laad de te vergelijken Excel files in de applicatie zodat de vergelijking geraporteerd kan worden.</h2>
            <div class="file has-name mg-small m-1">
                <label class="file-label">
                    <input id="file-apk-dmu" class="file-input" type="file" name="resume">
                    <span class="file-cta">
                        <span class="file-icon">
                            <i class="fas fa-upload"></i>
                        </span>
                        <span class="file-label">
                            APK DMU
                        </span>
                    </span>
                    <span id="file-name-apk" class="file-name">
                        Upload bestand...
                    </span>
                </label>
            </div>
            <div class="file has-name m-1">
                <label class="file-label">
                    <input id="file-fma-dmu" class="file-input" type="file" name="resume">
                    <span class="file-cta">
                        <span class="file-icon">
                            <i class="fas fa-upload"></i>
                        </span>
                        <span class="file-label">
                            FMA DMU
                        </span>
                    </span>
                    <span id="file-name-fma" class="file-name">
                        Upload bestand...
                    </span>
                </label>
            </div>
        </section>

        <section class="section">
            <h1 class="title">Rapport</h1>
            <h2 class="subtitle">Order nummers worden vergeleken en geraporteerd</h2>
            <button id="button-generate-report" class="button is-light">Genereer raport</button>
            <div class="content m-1">
                <table>
                    <thead>
                        <tr>
                            <th>Excel key Apk</th>
                            <th>Excel key Fma</th>
                            <th>Datum</th>
                            <th>Order nr.</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="table-report-body">

                    </tbody>
                </table>
            </div>
        </section>

    </body>

    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
    <script src="main.js"></script>
</html>
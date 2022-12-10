<html>
    <head>
    <title>FMA - APK DMU RAPORT | LOGIN</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <body>
    <section class="hero is-fullheight">
        <div class="hero-body">
            <div class="container">
            <div class="columns is-centered">
                <div class="column is-5-tablet is-4-desktop is-3-widescreen">
                <form action="" class="box">
                    <div class="field">
                    <label for="" class="label">Gebruiker</label>
                    <div class="control has-icons-left">
                        <input id="username" type="text" placeholder="admin" class="input" required>
                        <span class="icon is-small is-left">
                        <i class="fa fa-envelope"></i>
                        </span>
                    </div>
                    </div>
                    <div class="field">
                    <label for="" class="label">Wachwoord</label>
                    <div class="control has-icons-left">
                        <input id="password" type="password" placeholder="*******" class="input" required>
                        <span class="icon is-small is-left">
                        <i class="fa fa-lock"></i>
                        </span>
                    </div>
                    </div>
                    <div class="field">
                    <button id="submit" class="button is-info">
                        Inloggen
                    </button>
                    </div>
                </form>
                </div>
            </div>
            </div>
        </div>
        </section>
    </body>
</html>

<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function()
    {
        $('#submit').click(function(e) 
        {
            $.ajax({ url: 'verify.php', method: 'post', data: { username: $('#username').val(), password: $('#password').val() } }).then(function(e) { if (e == 1) { window.location.replace('index.php'); } });
        });
    });  
</script>
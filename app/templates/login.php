<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Log in</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
</head>
<body>
    <?php
    require_once 'connection.php';
    ?>
    <div class="container">
        <div id="login">
            <form method="post" action="/login">
                <div class="form-group">
                    <label for="loginF">Login</label>
                    <input type="text" placeholder="Enter login" name="login" id="loginF" class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" placeholder="Enter password" name="password" id="password" class="form-control"/>
                </div>
                <input type="submit" value="Log in" class="form-control"/>
            </form>
        </div>
    </div>
</body>
</html>

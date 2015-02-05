<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Sign in</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
</head>
<body>
    <?php
    require_once 'connection.php';
    ?>
    <div class="container">
        <div id="signin">
            <form method="post" action="/signin">
                <div class="form-group">
                    <label for="newLogin">Login</label>
                    <input type="text" placeholder="Enter login" name="newLogin" id="newLogin" class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="newPassword">Password</label>
                    <input type="password" placeholder="Enter password" name="newPassword" id="newPassword" class="form-control"/>
                </div>
                <input type="submit" value="Sign in" class="form-control"/>
            </form>
        </div>
    </div>
</body>
</html>

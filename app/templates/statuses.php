<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Statuses</title>
</head>
<body>
    <?php
        require_once 'connection.php';
    ?>
    <h1>Status list</h1>
    <?php
        foreach ($parameters['statuses'] as $status) :
            echo $status . '<br/><br/><br/>';
        endforeach;
    ?>
    <form action="/statuses" method="POST">
        <label for="message">Message:</label>
        <textarea name="message"></textarea>

        <input type="submit" value="Tweet!">
    </form>
</body>
</html>

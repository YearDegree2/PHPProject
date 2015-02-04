<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Status</title>
</head>
<body>
    <?php
        require_once 'connection.php';
        echo $parameters['status'];

        if (isset($_SESSION['username'])) {
            if ($_SESSION['username'] === $parameters['status']->getUsername()) {
                ?>
                <form action="/statuses/<?= $parameters['status']->getId() ?>" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" value="Delete">
                </form>
            <?php
            }
        }
    ?>
</body>
</html>

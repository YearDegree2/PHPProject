<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Status</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
    <?php
        require_once 'connection.php';
        $status =  $parameters['status'];
    ?>
        <br/><br/><br/>
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= $status->getUsername() ?> (on <?= $status->getClientUsed() ?>) <?= $status->getDate() ?>
                <?php
                if (isset($_SESSION['username']) && $_SESSION['username'] === $status->getUsername()) {
                    ?>

                    <form action="/statuses/<?= $status->getId() ?>" method="POST">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" value="Delete" class="btn btn-default"><span
                                class="glyphicon glyphicon-trash"></span></button>
                    </form>

                <?php
                }
                ?>

            </div>
            <div class="panel-body"><?= $status->getMessage() ?></div>
        </div>

    </div>
</body>
</html>

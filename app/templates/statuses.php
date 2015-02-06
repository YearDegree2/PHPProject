<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Statuses</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
</head>
<body>
    <?php
    require_once 'connection.php';
    ?>
    <div class="container">
        <h3><span class="label label-default">Message</span></h3>
        <form action="/statuses" method="POST">
            <label for="message"><h3><span class="label label-default">Message</span></h3></label><br/>
            <div class="input-group">
                <textarea name="message" class="form-control" placeholder="Message" id="message"></textarea>
                <span class="input-group-addon"><input type="submit" value="Tweet!" class="btn btn-default"></span>
            </div>
        </form><br/>

        <?php
            foreach ($parameters['statuses'] as $status) :
            ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= $status->getUsername() ?> (on <?= $status->getClientUsed() ?>) <?= $status->getDate() ?><br/>
                <a href="/statuses/<?= $status->getId() ?>" class="btn btn-default"><span class="glyphicon glyphicon-zoom-in"></span></a>
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

        <?php
            endforeach;
        ?>

    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Status</title>
</head>
<body>
    <?php
        echo $parameters['status'];
    ?>
    <form action="/statuses/<?= $parameters['status']->getId() ?>" method="POST">
        <input type="hidden" name="_method" value="DELETE">
        <input type="submit" value="Delete">
    </form>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Statuses</title>
</head>
<body>
    <h1>Status list</h1>
    <?php
        foreach ($parameters['statuses'] as $status) {
            echo $status . '<br/><br/><br/>';
        }
    ?>
</body>
</html>

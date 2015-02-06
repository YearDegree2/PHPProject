<nav class="navbar navbar-inverse  navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="/">Home</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">

<?php
if (!isset($_SESSION['username'])) {
    ?>

                <li>
                    <a href="/signin">Sign In</a>
                </li>
                <li>
                    <a href="/login">Log In</a>
                </li>

<?php
}
if (isset($_SESSION['username'])) {
    ?>
                <li>
                    <a href="/logout">Log Out</a>
                </li>

<?php
}
if (isset($_SESSION['page'])) {
    switch ($_SESSION['page']) {
        case "index":
            if (isset($_SESSION['username'])) {
                ?>

                <li>
                    <a href="/statuses/<?= $_SESSION['username']?>">List statuses you wrote</a>
                </li>

            <?php
            }
            break;
        case "status":
            if (isset($_SESSION['username'])) {
                ?>

                <li>
                    <a href="/statuses/<?= $_SESSION['username']?>">List statuses you wrote</a>
                </li>

            <?php
            }
            break;
    }
}
if (isset($_SESSION['username'])) {
    ?>

                <li>
                    <a><?=$_SESSION['username']?></strong> is connected</a>
                </li>

<?php
}
?>
            </ul>
        </div>
    </div>
</nav>

<?php
if (!isset($_SESSION['username'])) {
    ?>

    <form action="/signin" method="GET">
        <input type="submit" class="submit" value="Sign in"/>
    </form>
    <form action="/login" method="GET">
        <input type="submit" class="submit" value="Log in"/>
    </form>

<?php
}
if (isset($_SESSION['username'])) {
    echo $_SESSION['username'] . ' is connected';
    ?>

    <form action="/logout" method="GET">
        <input type="submit" class="submit" value="Log out"/>
    </form>

<?php
}
if (isset($_SESSION['page'])) {
    switch ($_SESSION['page']) {
        case "index":
            if (isset($_SESSION['username'])) {
                ?>

                <form action="/statuses/<?=$_SESSION['username']?>" method="GET">
                    <input type="submit" class="submit" value="List statuses you wrote"/>
                </form>

            <?php
            }
            break;

        case "indexByPeople":
            ?>

            <form action="/" method="GET">
                <input type="submit" class="submit" value="Index"/>
            </form>

            <?php
            break;

        case "status":
            ?>

            <form action="/" method="GET">
                <input type="submit" class="submit" value="Index"/>
            </form>

            <?php
            if (isset($_SESSION['username'])) {
                ?>

                <form action="/statuses/<?= $_SESSION['username'] ?>" method="GET">
                    <input type="submit" class="submit" value="List statuses you wrote"/>
                </form>

            <?php
            }
            break;
    }
}

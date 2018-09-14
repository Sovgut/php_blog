<?php

use components\Essentials\Environment;

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible"
          content="IE=edge">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <title><?= $app->name ?></title>
    <link rel="stylesheet"
          type="text/css"
          media="screen"
          href="assets/css/main.css" />

</head>

<body>

    <!-- HEADER -->
    <nav class="navbar navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand"
               href="/"><?= $app->name ?></a>
            <form class="form-inline my-2 my-lg-0"
                  action="/"
                  method="GET">
                <input class="form-control mr-sm-2"
                       type="search"
                       placeholder="Search"
                       aria-label="Search"
                       name="search">
                <input type="hidden"
                       name="page"
                       value="<?= isset(Environment::Request()->page) ? Environment::Request()->page : 'index' ?>" />
                <button class="btn btn-outline-success my-2 my-sm-0"
                        type="submit">Search</button>
            </form>
        </div>
    </nav>

    <!-- CONTENT -->
    <?= Environment::Route() ?>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <p><?= $app->name ?> &copy;
                        <?= date("Y") ?>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>
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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet"
          type="text/css"
          media="screen"
          href="assets/css/main.css" />

</head>

<body>

    <!-- HEADER -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a  class="navbar-brand"
                href="/">
                <?= $app->name ?>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-toggle="modal" data-target="#addPostModal">Create post</a>
                    </li>
                </ul>
                <form class="form-inline mr-3 header-form" action="/" method="GET">
                    <input class="form-control" name="search" type="search" placeholder="Search" aria-label="Search">
                    <input type="hidden" name="page" value="index"/>
                </form>
                <div>
                    <button type="button" class="btn btn-primary login-button"><span><i class="fab fa-google"></i></span> Sign in</button>
                </div>
            </div>
        </div>
    </nav>

    <!-- FIX SPACING WITH FIXED NAVBAR -->
    <div class="mt-5 mb-5">&nbsp;</div>

    <!-- CONTENT -->
    <?= Environment::Route() ?>

    <!-- FOOTER -->
    <footer class="mt-5 pt-5 pb-5">
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

    <!-- Modal -->
    <div class="modal fade" id="addPostModal" tabindex="-1" role="dialog" aria-labelledby="addPostModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPostModalTitle">NEW POST</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="/" method="post">
                        <div class="form-group">
                            <label for="addPostTitle">Title</label>
                            <input type="text" name="title" class="form-control" id="addPostTitle" placeholder="Title" required>
                        </div>
                        <input type="hidden" name="MAX_FILE_SIZE" value="1024000000" />
                        <div class="form-group">
                            <label for="addPostImage">Image</label>
                            <input type="file" name="image" class="form-control-file" id="addPostImage" required>
                        </div>
                        <div class="form-group">
                            <label for="addPostCategory">Category</label>
                            <select class="form-control" name="category" id="addPostCategory">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="addPostDescription">Description</label>
                            <textarea class="form-control" placeholder="Description" name="description" id="addPostDescription" maxlength="1024" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="addPostContent">Content</label>
                            <textarea class="form-control" placeholder="Content" name="content" id="addPostContent" rows="3" required></textarea>
                        </div>
                        <input type="hidden" name="page" value="addPost"/>
                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>

</html>
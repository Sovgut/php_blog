<!-- Blog Sidebar Widgets Column -->
<?php
    $famillyCount = "";
    $workCount="";
    $freelanceCount = "";
    $relaxationСount = "";
    try{
        $dsn="sqlite:blog.sqlite";
        $db = new PDO($dsn);
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $db->beginTransaction();
        $sql = "select count(id) from posts where category_id=1";
        foreach($db->query($sql) as $row){$famillyCount=$row['count(id)'];}
        $sql = "select count(id) from posts where category_id=2";
        foreach($db->query($sql) as $row){$workCount=$row['count(id)'];}
        $sql = "select count(id) from posts where category_id=3";
        foreach($db->query($sql) as $row){$freelanceCount=$row['count(id)'];}
        $sql = "select count(id) from posts where category_id=4";
        foreach($db->query($sql) as $row){$relaxationСount=$row['count(id)'];}

    }catch(PDOException $ex){
        $db->rollBack();
        echo $ex->getMessage();
    }
?>
<div class="col-md-4" >

    <!-- Blog Search Well -->
    <div class="well">
        <h4>Поиск в блоге</h4>
        <form action="index.php" method="post">
            <div class="input-group">
                <input type="text" class="form-control" name="searchText" value="<?php if(isset($_POST['searchText']))echo $_POST['searchText'];if(isset($_GET['searchText'])) echo $_GET['searchText'];?>">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit" name="btnSearch">
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>
                            </span>
            </div>
        </form>
        <!-- /.input-group -->
    </div>

    <!-- Blog Categories Well -->
    <div class="well">
        <h4>Категории новостей</h4>
        <div class="row">
            <div class="col-xs-12">
                <ul class="list-unstyled">
                    <li><a href="category.php?category_id=1">Семья <span class="countCategory bg-info"> <?=$famillyCount?></span> </a>
                    </li>
                    <li><a href="category.php?category_id=2">Работа <span class="countCategory bg-info"> <?=$workCount?></span></a>
                    </li>
                    <li><a href="category.php?category_id=3">Фриланс<span class="countCategory bg-info"><?=$freelanceCount?></span></a>
                    </li>
                    <li><a href="category.php?category_id=4">Отдых <span class="countCategory bg-info"> <?=$relaxationСount?></span></a>
                    </li>
                </ul>
            </div>
            <!-- /.col-xs-12 -->
        </div>
        <!-- /.row -->
    </div>

    <!-- Side Widget Well -->
    <div class="well">
        <div class="widget">
            <h4>Кратко о блоге</h4>
            <p>В этом блоге я рассказываю о себе, своих родных, друзей.</p>
            <img src="images/myfamily.jpg" alt="myfamilly" class="img-responsive">
        </div>
    </div>
</div>


<!-- /.row -->

<hr>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <p>Copyright &copy; Your Website 2014</p>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </div>
</footer>

</div>
<!-- /.container -->

<!--Modal Content-->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document"aria-hidden="true">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="myModalLabel1">Добавим пост!</h4>
            </div>
            <form id="formAddPost" action="#" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="well">
                        <div class="form-group">
                            <label for="byAuthor">Автор</label>
                            <input class="form-control" type="text" name="byAuthor" id="byAuthor" placeholder="Введите Ваше имя...">
                            <div id="errorByAuthor"></div>
                        </div>
                        <div class="form-group">
                            <label for="title">Заголовок поста</label>
                            <input class="form-control" type="text" name="title" id="title" placeholder="Введите заголовок...">
                            <div id="errorTitle"></div>
                        </div>
                        <div class="form-group">
                            <label for="content">Текст поста</label>
                            <textarea rows="4" class="form-control" name="content" id="content" placeholder="Раскройте тему сдесь..."></textarea>
                            <div id="errorContent"></div>
                        </div>
                        <div class="form-group">
                            <label for="data">Выбор изображения</label>
                            <input type="file" name="data" id="data">
                            <div id="errorFile"></div>
                        </div>
                        <div class="form-group">
                            <label for="category">Категория поста</label>
                            <select class="form-control" name="category" id="category">
                               <option value="1" selected="selected">Семья</option>
                                <option value="2">Работа</option>
                                <option value="3">Фриланс</option>
                                <option value="4">Отдых</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="addPost" id="addPost" class="btn btn-primary">Добавить</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/functions.js"></script>
</body>

</html>
<?php
$titlePage = "Post";
try {
    $dsn = "sqlite:blog.sqlite";
    $db = new PDO($dsn);
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $db->beginTransaction();
    $path_img = "images/9.jpg";
    $dbdate = date("F d, Y")." at ".date("H:i A");

    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $st=$db->prepare("select * from posts,categories where posts.category_id=categories.id and posts.id=:id");
        $st->execute(array('id'=>$id));
        foreach($st->fetchAll() as $row){
            $titlePost=$row['title'];
            $nameCategory = $row['name'];
            $published_date = $row['published_date'];
            $path_img=$row['path_img'];
            $content = $row['content'];
        }
    }

}catch(PDOException $ex){
    $db->rollBack();
    echo "<p style='color:red'>{$ex->getMessage()}</p>";
}

if(isset($_POST['addComment'])){
    if($_POST['nameUser']!=""&&$_POST['textComment']!=""){
        $nameUser = $_POST['nameUser'];
        $textComment = $_POST['textComment'];
        $dbdate = date("F d, Y")." at ".date("H:i A");
        $idPost = $_GET['id'];
        $st = $db->prepare("insert into comments(comment_text,post_id,nameUser,published_date)VALUES(:textComment,:idPost,:nameUser,:dbdate)");
        $st->bindParam(':textComment',$textComment);
        $st->bindParam(':idPost',$idPost);
        $st->bindParam(':nameUser',$nameUser);
        $st->bindParam(':dbdate',$dbdate);
        $st->execute();
        $db->commit();
    }

}


include_once "header.php"
?>

    <!-- Page Content -->
    <div class="container">
        <ul class="pager">
            <li class="previous">
                <a href="index.php">&larr; На главную</a>
            </li>
        </ul>
        <div class="row">

            <!-- Blog Post Content Column -->
            <div class="col-lg-8">

                <!-- Blog Post -->

                <!-- Title -->
                <h1><?=$titlePost?></h1>

                <!-- Author -->
                <p class="lead">
                    Категория:  <a href="category.php?category_id=<?=$row['id']?>"><?=$nameCategory?></a>
                </p>

                <hr>

                <!-- Date/Time -->
                <p><span class="glyphicon glyphicon-time"></span> Опубликовано <?=$published_date?><span class="author bg-info pull-right"> Oт <?=$row['authorName']?></span></p>

                <hr>

                <!-- Preview Image -->
                <img class="img-responsive" src="<?=$path_img?>" alt="">

                <hr>

                <!-- Post Content -->
                <p><?=htmlentities($content,ENT_IGNORE)?></p>
                <hr>

                <!-- Blog Comments -->

                <!-- Comments Form -->
                <div class="well">
                    <h4>Оставьте комнтарий:</h4>
                    <form role="form" method="post" id="comments">
                        <div class="form-group">
                            <label for="name">Имя</label>
                            <input class="form-control" type="text" name="nameUser" id="name" placeholder="Введите ваше имя...">
                            <div id="errorName"></div>
                        </div>
                        <div class="form-group">
                            <label for="comment">Коментарий</label>
                            <textarea id="comment" name="textComment" class="form-control" rows="3" placeholder="Пишите коментарий..."></textarea>
                            <div id="errorComment"></div>
                        </div>
                        <button type="submit" name="addComment" class="btn btn-primary">Добавить</button>
                    </form>
                </div>

                <hr>

                <!-- Posted Comments -->

                <!-- Comment -->
                <?php
                $st = $db->prepare("select * from comments where post_id=:id ORDER by id DESC");
                $st->execute(array('id'=>$id));
                foreach($st->fetchAll() as $row):?>
                <div class="media">
                    <a class="pull-left userPhoto" href="#">
                        <img class="media-object img-responsive" src="images/User.png" alt="">
                    </a>
                    <div class="media-body">
                        <h4 class="media-heading"><?=$row['nameUser']?>
                            <small><?=$row['published_date']?></small>
                        </h4>
                        <?=$row['comment_text']?>
                    </div>
                </div>
                <?php endforeach;?>
                 <ul class="pager">
                    <li class="previous">
                        <a href="index.php">&larr; На главную</a>
                    </li>
                </ul>

            </div>

            <?php include_once "footer.php";?>

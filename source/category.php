<?php

if(isset($_GET['category_id'])) {
    $getCategory=$_GET['category_id'];
    try {
        $dsn = "sqlite:blog.sqlite";
        $db = new PDO($dsn);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->beginTransaction();

        if (!isset($_GET['offset'])) {
            $offset = 0;
        } else {
            $offset = $_GET['offset'];
        }


        $st = $db->prepare("select count(id) from posts WHERE category_id=:getCategory");
        $st->execute(array('getCategory'=>$getCategory));
        $countPosts = "";
        foreach ($st->fetchAll() as $row) {
            $countPosts = $row['count(id)'];
        }
        $st = $db->prepare("select name from categories where id=:getCategory");
        $st->execute(array('getCategory'=>$getCategory));
        foreach ($st->fetchAll() as $row) {
            $titlePage = $row['name'];
        }
    } catch (PDOException $ex) {
        $db->rollBack();
        echo "<p style='color:red'>{$ex->getMessage()}</p>";
    }
}

include_once "header.php";

?>

<!-- Page Content -->
<div class="container">
    <ul class="pager">
        <li class="previous">
            <a href="index.php">&larr; На главную</a>
        </li>
    </ul>
    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">


            <h1 class="page-header">
                Категория: <?=$titlePage?>
                <small> </small>
            </h1>
            <ul class="pager">
                <li class="previous <?php echo $offset==0?'hide':'show'?>">
                    <a href="category.php?category_id=<?=$getCategory?>&offset=<?php echo $offset-4?>">&larr; Новые</a>
                </li>
                <li class="next <?php echo $offset+4>=$countPosts?'hide':'show'?>">
                    <a href="category.php?category_id=<?=$getCategory?>&offset=<?php echo $offset+4?>">Старые &rarr;</a>
                </li>
            </ul>
            <!--Blog Posts -->
            <?php
            $st=$db->prepare("select * from posts,categories where posts.category_id=categories.id and categories.id=:category_id order by posts.id desc limit :offset,4");
            $st->execute(array('category_id'=>$_GET['category_id'],'offset'=>$offset));
            foreach($st->fetchAll() as $row):?>
                <h2>
                    <a href="post.php?id=<?=$row[0]?>"><?=$row['title']?></a>
                </h2>
                <p class="lead">
                    Категория:  <a href="category.php?category_id=<?=$row['id']?>"><?=$row['name']?></a>
                </p>
                <p><span class="glyphicon glyphicon-time"></span> Опубликовано <?=$row['published_date']?><span class="author pull-right bg-info"> Oт <?=$row['authorName']?></span></p>

                <hr>

                <img class="img-responsive" src="<?=$row['path_img']?>" alt="">
                <hr>
                <p><?php
                    $str=substr(substr($row['content'],0,400),0,-10);
                    echo htmlentities($str,ENT_IGNORE).'...';
                    ?>
                </p>
                <a class="btn btn-primary" href="post.php?id=<?=$row[0]?>">Читать больше<span class="glyphicon glyphicon-chevron-right"></span></a>
                <hr>
            <?php endforeach;?>


            <!-- Pager -->
            <ul class="pager">
                <li class="previous <?php echo $offset==0?'hide':'show'?>">
                    <a href="category.php?category_id=<?=$getCategory?>&offset=<?php echo $offset-4?>">&larr; Новые</a>
                </li>
                <li class="next <?php echo $offset+4>=$countPosts?'hide':'show'?>">
                    <a href="category.php?category_id=<?=$getCategory?>&offset=<?php echo $offset+4?>">Старые &rarr;</a>
                </li>
            </ul>
        </div>

        <?php include_once "footer.php";?>

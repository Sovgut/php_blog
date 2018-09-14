<?php

$titlePage = "Home";
include_once "header.php";
try {
    $dsn = "sqlite:blog.sqlite";
    $db = new PDO($dsn);
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $db->beginTransaction();

    if(!isset($_GET['offset'])){
        $offset = 0;
    }else{
        $offset = $_GET['offset'];
    }

    if(isset($_POST['btnSearch']) || isset($_GET['searchText'])) {
        if(isset($_POST['searchText'])) {
            $searchText = $_POST['searchText'];
        }else{
            $searchText = $_GET['searchText'];
        }
        $st = $db->prepare("SELECT * FROM posts,categories WHERE posts.category_id=categories.id and posts.title like :searchText ORDER BY posts.id DESC LIMIT :offset,4");
        $st->execute(array('offset' => $offset,'searchText'=>"%$searchText%"));
        $stc=$db->prepare("select count(id) from posts where title like :filter");
        $stc->execute(array('filter'=>"%$searchText%"));
        foreach($stc->fetchAll() as $row){
            $countPosts = $row['count(id)'];
        }
    }else{
        $st = $db->prepare("SELECT * FROM posts,categories WHERE posts.category_id=categories.id ORDER BY posts.id DESC LIMIT :offset,4");
        $st->execute(array('offset' => $offset));
        $sqlCount = "select count(id) from posts";
        $countPosts="";
        foreach($db->query($sqlCount) as $row){$countPosts=$row['count(id)'];}
    }




}catch(PDOException $ex){
    $db->rollBack();
    echo "<p style='color:red'>{$ex->getMessage()}</p>";
}


?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">


                <h1 class="page-header">
                    <?php if(isset($_POST['btnSearch']) || isset($_GET['searchText'])){
                        echo "Результаты поиска <small>\"$searchText\"</small>";
                    }else{
                    ?>
                    Разные темы
                    <small>Всех категорий</small>
                    <?php }?>
                </h1>
                <ul class="pager">
                    <li class="previous <?php echo $offset==0?'hide':'show'?>">
                        <a href="index.php?<?php if(isset($searchText)):?>searchText=<?=$searchText?>&<?php endif;?>offset=<?php echo $offset-4?>">&larr; Новые</a>
                    </li>
                    <li class="next <?php echo $offset+4>=$countPosts?'hide':'show'?>">
                        <a href="index.php?<?php if(isset($searchText)):?>searchText=<?=$searchText?>&<?php endif;?>offset=<?php echo $offset+4?>">Старые &rarr;</a>
                    </li>
                </ul>
                <!--Blog Posts -->
                <?php

                foreach($st->fetchAll() as $row):?>

                <h2>
                    <a href="post.php?id=<?=$row[0]?>"><?=$row['title']?></a>
                </h2>
                <p class="lead">
                    Категория:  <a href="category.php?category_id=<?=$row['id']?>"><?=$row['name']?></a>

                </p>
                <span class="glyphicon glyphicon-time"></span> Опубликовано <?=$row['published_date']?>
                    <span class="author bg-info pull-right"> Oт <?=$row['authorName']?></span>
                <hr>
                <img class="img-responsive" src="<?=$row['path_img']?>" alt="">
                <hr>
                <p><?php
                        $str=substr($row['content'],0,450);
                        echo htmlentities($str,ENT_IGNORE)."...";
                    ?>
                </p>
                <a class="btn btn-primary" href="post.php?id=<?=$row[0]?>">Читать больше<span class="glyphicon glyphicon-chevron-right"></span></a>
                <hr>
                <?php endforeach;?>


                <!-- Pager -->
                <ul class="pager">
                    <li class="previous <?php echo $offset==0?'hide':'show'?>">
                        <a href="index.php?<?php if(isset($searchText)):?>searchText=<?=$searchText?>&<?php endif;?>offset=<?php echo $offset-4?>">&larr; Новые</a>
                    </li>
                    <li class="next <?php echo $offset+4>=$countPosts?'hide':'show'?>">
                        <a href="index.php?<?php if(isset($searchText)):?>searchText=<?=$searchText?>&<?php endif;?>offset=<?php echo $offset+4?>">Старые &rarr;</a>
                    </li>
                </ul>
            </div>

<?php include_once "footer.php";?>

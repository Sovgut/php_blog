<?php
    if(isset($_POST['addPost'])){
        //переменные для проверки
        $title=$_POST['title'];
        $content = $_POST['content'];
        $byAuthor = $_POST['byAuthor'];
        $isChoiseFile = empty($_FILES);
        if($title!="" && $content!=""&& $byAuthor!=""){
            try{
                //переменные для вставки поста
                $dbdate = date("F d, Y")." at ".date("H:i A");
                $pathImage ="";
                $uploadDir ="images";
                $category = intval($_POST['category']);

                //подключение к базе данных
                $dsn = "sqlite:blog.sqlite";
                $db=new PDO($dsn);
                $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                $db->beginTransaction();

                //загрузка изображения
                if(!is_dir($uploadDir)){
                    mkdir($uploadDir);
                }

                if(isset($_FILES['data'])){
                    if($_FILES['data']['error']==UPLOAD_ERR_OK){
                        $src = $_FILES['data']["tmp_name"];
                        $fname = $_FILES['data']['name'];
                        $pathImage = $uploadDir."/".$fname;
                        move_uploaded_file($src,$pathImage);
                    }
                }


                $st = $db->prepare("insert into posts(category_id,published_date,path_img,title,content,authorName)values(:category,:dbdate,:pathImage,:title,:content,:byAuthor)");
                $st->bindParam(':category',$category);
                $st->bindParam(':dbdate',$dbdate);
                $st->bindParam(':pathImage',$pathImage);
                $st->bindParam(':title',$title);
                $st->bindParam(':content',$content);
                $st->bindParam(':byAuthor',$byAuthor);
                $st->execute();
                $db->commit();
            }catch(PDOException $ex){
                $db->rollBack();
                echo "<p style='color:red'>{$ex->getMessage()}</p>";
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?=$titlePage;?></title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/main.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<!-- Navigation -->

<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="row">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">

                    Блог AS
                </a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a data-toggle="modal" data-target="#myModal" href="#" id="btnMenu">Добавить пост</a>
                    </li>
<!--                    <li>-->
<!--                        <a href="#">Обо мне</a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a href="#">Сервисы</a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a href="#">Контакты</a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a href="#">Регистрация</a>-->
<!--                    </li>-->
                </ul>
            </div>
        <!-- /.navbar-collapse -->
        </div>
    </div>
    <!-- /.container -->
</nav>
<?php 

use components\Essentials\Environment;

?>

<div class="container">
    <div class="card text-center mt-5 mb-5">
        <div class="card-header">
            <a href="<?= Environment::HtmlRoute('post', ['id' => $this->post->id]) ?>">
                <?= $this->post->title ?></a> -
            <small class="text-muted">
                <a href="<?= Environment::HtmlRoute('category', ['id' => $this->post->category_id]) ?>">
                    <?= $this->post->name ?></a>
            </small>
        </div>
        <div class="card-body p-0 pb-3">
            <a href="<?= Environment::HtmlRoute('post', ['id' => $this->post->id]) ?>">
                <img class="img-fluid mb-3"
                     src="<?= $this->post->path_img ?>"
                     alt="<?= $this->post->title ?>" />
            </a>
            <p class="card-text">
                <?= $this->post->content ?>
            </p>
        </div>
        <div class="card-footer text-muted">
            <div class="row">
                <div class="col-lg-6 text-left">
                    <small>
                        <?= $this->post->authorName ?>
                    </small>
                </div>
                <div class="col-lg-6 text-right">
                    <small>
                        <?= $this->post->published_date ?></small>
                </div>
            </div>
        </div>
    </div>

    <!-- COMMENTS -->

    <h5 class="text-muted text-center">Comments</h5>
    <hr class="mb-5"/>

    <?php foreach($this->comments as $comment) : ?>

        <div class="card mb-3">
            <div class="card-header">
                <div class="row">
                    <div class="col-6 text-left">
                        <?= $comment->nameUser ?>
                    </div>
                    <div class="col-6 text-muted text-right">
                        <small><?= $comment->published_date ?></small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p class="card-text"><?= $comment->comment_text ?></p>
            </div>
        </div>

    <?php endforeach; ?>
</div>
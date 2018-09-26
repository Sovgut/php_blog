<?php 

use components\Essentials\Environment;

?>

<div class="container">

    <!-- IF POSTS NOT FOUND -->
    <?php if (empty($this->posts)) : ?>
    <p>Posts not found</p>
    <?php else : ?>

    <!-- POSTS -->
    <?php foreach ($this->posts as $post) : ?>
    <div class="card text-center mt-5 mb-5">
        <div class="card-header">
            <a href="<?= Environment::HtmlRoute('post', ['id' => $post->id]) ?>">
                <?= $post->title ?></a> -
            <small class="text-muted">
                <a href="<?= Environment::HtmlRoute('category', ['id' => $post->category_id]) ?>">
                    <?= $post->name ?></a>
            </small>
        </div>
        <div class="card-body p-0 pb-3">
            <a href="<?= Environment::HtmlRoute('post', ['id' => $post->id]) ?>">
                <img class="img-fluid mb-3"
                     src="<?= $post->path_img ?>"
                     alt="<?= $post->title ?>" />
            </a>
            <p class="card-text">
                <?= $post->content ?>
            </p>
        </div>
        <div class="card-footer text-muted">
            <div class="row">
                <div class="col-lg-6 text-left">
                    <small>
                        <?= $post->authorName ?>
                    </small>
                </div>
                <div class="col-lg-6 text-right">
                    <small>
                        <?= $post->published_date ?></small>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>

    <!-- PAGINATION -->
    <nav aria-label="navigation">
        <ul class="pagination justify-content-center">

            <!-- SHOW BACK BUTTON IF AVAILABLE -->
            <?php if ($this->offset > 0) : ?>
            <li class="page-item"><a class="page-link"
                   href="<?= Environment::HtmlRoute('category', ['offset' => $this->offset - $this->perPage, 'id' => $this->id]) ?>">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                </a></li>
            <?php endif; ?>

            <!-- SHOW PAGES BUTTONS -->
            <?php for ($page = 0; $page < $this->count; $page++) : ?>
            <li class="page-item <?= $page * $this->perPage == $this->offset ? 'active' : '' ?>"><a class="page-link"
                   href="<?= Environment::HtmlRoute('category', ['offset' => $page * $this->perPage, 'id' => $this->id]) ?>">
                    <?= $page + 1 ?></a></li>
            <?php endfor; ?>

            <!-- SHOW NEXT BUTTON IF AVAILABLE -->
            <?php if ($this->offset + $this->perPage < $this->count * $this->perPage) : ?>
            <li class="page-item"><a class="page-link"
                   href="<?= Environment::HtmlRoute('category', ['offset' => $this->offset + $this->perPage, 'id' => $this->id]) ?>">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a></li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
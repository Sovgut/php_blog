<div class="container">

    <!-- POSTS -->
    <?php foreach ($this->posts as $post) : ?>
    <div class="card text-center mt-5 mb-5">
        <div class="card-header">
            <?= $post->title ?> - <small class="text-muted">
                <?= $post->name ?></small>
        </div>
        <div class="card-body p-0 pb-3">
            <img class="img-fluid mb-3"
                 src="<?= $post->path_img ?>"
                 alt="<?= $post->title ?>" />
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

    <!-- PAGINATION -->
    <nav aria-label="navigation">
        <ul class="pagination">
            <?php if ($this->offset > 0) : ?>
            <li class="page-item"><a class="page-link"
                   href="/?page=index&offset=<?= $this->offset - $this->perPage ?>&search=<?= $this->search ?>">Previous</a></li>
            <?php endif; ?>
            <?php for ($page = 0; $page < $this->count; $page++) : ?>
            <li class="page-item <?= $page * $this->perPage == $this->offset ? 'active' : '' ?>"><a
                   class="page-link"
                   href="/?page=index&offset=<?= $page * $this->perPage ?>&search=<?= $this->search ?>">
                    <?= $page + 1 ?></a></li>
            <?php endfor; ?>
            <?php if ($this->offset + $this->perPage < $this->count * $this->perPage) : ?>
            <li class="page-item"><a class="page-link"
                   href="/?page=index&offset=<?= $this->offset + $this->perPage ?>&search=<?= $this->search ?>">Next</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
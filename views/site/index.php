<?php

/** @var yii\web\View $this */
\app\assets\AppAsset::register($this);
$this->title = 'BookGuide';
?>
<div class="site-index">
    <div class="homeContent  ">
        <div class="homeRow  ">
            <div class="col-lg-4">
                <h2>Books</h2>
                <a href="/book/index"><img src="/icon/openBook.png"></a>
            </div>
            <div class="col-lg-4">
                <h2>Authors</h2>
                <a href="/author/index"><img src="/icon/openAuthor.jpg"></a>
            </div>
        </div>

    </div>
</div>

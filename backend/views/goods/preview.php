<div class="container">
    <h1><?=$goods->name?></h1>
    <div id="w0" class="carousel">

        <div class="carousel-inner">
            <?php foreach ($photos as $photo):?>

                <img src=<?=$photo->path?> alt="">

            <?php endforeach;?>

        </div>
    </div>
    <p>
        <?=$contents->content?>
    </p>
</div>
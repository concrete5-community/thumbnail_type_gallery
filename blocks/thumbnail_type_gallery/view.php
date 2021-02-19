<?php
defined('C5_EXECUTE') or die('Access Denied.');

/** @var bool $hasImages */
/** @var \Concrete\Core\Page\Page $c */
/** @var \A3020\ThumbnailTypeGallery\Image[] $images */
/** @var bool $show_captions */

if (!$hasImages && $c->isEditMode()) {
    // This could happen e.g. if a thumbnail type or file set has been removed
    ?><p><?php echo t("No images can be displayed."); ?></p><?php
    return;
}

if (!$hasImages) {
    return;
}
?>

<div class="js-thumbnail-type-gallery thumbnail-type-gallery <?php echo $bID; ?>">
    <div class="ttg-images">
        <?php
        foreach ($images as $image) {
            ?>
            <a class="item" href="<?php echo $image->getUrlLarge(); ?>"
               <?php
               if ($show_captions) {
                   echo 'title="'.h($image->getCaption()).'"';
               }
               ?>>
                <div class="image">
                    <img src="<?php echo $image->getUrlSmall() ?>"
                         alt="<?php echo h($image->getAlt()); ?>"/>
                </div>
            </a>
            <?php
        }
        ?>
    </div>

    <?php
    if (isset($pagination)) {
        ?>
        <div class="ttg-pagination">
            <?php
            echo $pagination;
            ?>
        </div>
        <?php
    }
    ?>
</div>

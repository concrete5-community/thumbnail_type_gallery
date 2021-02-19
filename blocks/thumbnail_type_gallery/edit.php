<?php

defined('C5_EXECUTE') or die('Access Denied.');

/** @var \Concrete\Core\Application\Application $app */
/** @var int $file_set_id */
/** @var array $fileSetOptions */
/** @var int $small_thumbnail_type_id */
/** @var array $smallThumbnailTypeOptions */
/** @var int $large_thumbnail_type_id */
/** @var array $largeThumbnailTypeOptions */
/** @var bool $show_captions */
/** @var int $styling */

if (count($fileSetOptions) === 0) {
    ?>
    <div class="alert alert-info">
        <p>
            <?php
            echo t("You haven't created any file sets yet. Please create a file set first.");
            ?>
        </p>
        <br>

        <a class="btn btn-primary" href="<?php echo $app->make('url/manager')->resolve(['/dashboard/files/add_set']); ?>">
            <?php
            echo t('Create file set');
            ?>
        </a>
    </div>
    <?php

    return;
}
?>

<div class="form-group">
    <?php
    echo $form->label('file_set_id', t('File set').' *');
    echo $form->select('file_set_id', $fileSetOptions, $file_set_id, [
        'required' => 'required',
    ]);
    ?>
</div>

<div class="form-group">
    <?php
    echo $form->label('small_thumbnail_type_id', t('Thumbnail type for small images').' *');
    echo $form->select('small_thumbnail_type_id', $smallThumbnailTypeOptions, $small_thumbnail_type_id, [
        'required' => 'required',
    ]);
    ?>
</div>

<div class="form-group">
    <?php
    echo $form->label('large_thumbnail_type_id', t('Thumbnail type for large images').' *');
    echo $form->select('large_thumbnail_type_id', $largeThumbnailTypeOptions, $large_thumbnail_type_id, [
        'required' => 'required',
    ]);
    ?>
</div>

<div class="form-group">
    <?php
    echo $form->label('styling', t('Styling').' *');
    echo $form->select('styling', [
        1 => t('Basic styling'),
        0 => t('No styling'),
    ], $styling);
    ?>
</div>

<div class="form-group">
    <div class="checkbox">
        <label>
            <?php echo $form->checkbox('show_captions', 1, $show_captions); ?>
            <?php echo t('Show captions in popup'); ?>
        </label>
    </div>
</div>


<div class="form-group">
    <?php
    echo $form->label('items_per_page', t('Maximum number of thumnails per page'));
    echo $form->number('items_per_page', $items_per_page, [
        'placeholder' => 100,
    ])
    ?>
</div>
